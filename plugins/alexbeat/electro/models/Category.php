<?php

namespace Alexbeat\Electro\Models;

use Model;
use DB;

class Category extends Model
{
    use \October\Rain\Database\Traits\SimpleTree;
    use \October\Rain\Database\Traits\Sortable;

    public $table = 'oc_category';
    public $primaryKey = 'category_id';
    public $incrementing = true;
    public $timestamps = false;

    // Связи
    public $hasOne = [
        'category_description' => [
            'Alexbeat\Electro\Models\CategoryDescription',
            'key' => 'category_id',
            'otherKey' => 'category_id',
            'conditions' => 'language_id = 1'
        ],
    ];

    public $hasMany = [
        'brands' => [
            'Alexbeat\Electro\Models\CategoryBrand',
            'key' => 'category_id',
            'otherKey' => 'category_id'
        ],
        'faq' => [
            'Alexbeat\Electro\Models\CategoryFaq',
            'key' => 'category_id',
            'otherKey' => 'category_id'
        ],
        'links' => [
            'Alexbeat\Electro\Models\CategoryLink',
            'key' => 'category_id',
            'otherKey' => 'category_id'
        ],
        // 'descriptions' => [
        //     'Alexbeat\Electro\Models\CategoryDescription',
        //     'key' => 'category_id',
        //     'otherKey' => 'category_id',
        // ],
        'stores' => [
            'Alexbeat\Electro\Models\CategoryToStore',
            'key' => 'category_id',
            'otherKey' => 'category_id'
        ],
        'paths' => [
            'Alexbeat\Electro\Models\CategoryPath',
            'key' => 'category_id',
            'otherKey' => 'category_id'
        ],
    ];

    public $belongsToMany = [
        'products' => [
            'Alexbeat\Electro\Models\Product',
            'table' => 'oc_product_to_category',
            'key' => 'category_id',
            'otherKey' => 'product_id',
            'pivot' => ['product_id', 'category_id', 'main_category'],
        ],
        'filter_attributes' => [
            'Alexbeat\Electro\Models\Attribute',
            'table' => 'oc_category_filter_attributes',
            'key' => 'category_id',
            'otherKey' => 'attribute_id',
            'pivot' => [
                'sort_order',
                'show_in_card',
            ],
        ],
    ];

    // Функция для получения всех подкатегорий рекурсивно
    public function getAllSubCategories($categoryIds)
    {
        $allCategoryIds = [];

        // Получаем все подкатегории для каждого указанного ID категории
        foreach ($categoryIds as $categoryId) {
            $allCategoryIds[] = $categoryId;
            $subCategoryIds = $this->getSubCategoryIds($categoryId);
            if (!empty($subCategoryIds)) {
                $allCategoryIds = array_merge($allCategoryIds, $this->getAllSubCategories($subCategoryIds));
            }
        }

        return $allCategoryIds;
    }

    // Функция для получения прямых подкатегорий для одной категории
    protected function getSubCategoryIds($categoryId)
    {
        return $this->where('parent_id', $categoryId)->pluck('category_id')->toArray();
    }

    private function getAttr($name)
    {
        return $this->category_description ? $this->category_description->$name : null;
    }

    private function setAttr($name, $value)
    {
        $this->category_description->$name = $value;
        $this->category_description->save();
    }

    //category_description getters

    public function getNameAttribute()
    {
        return $this->getAttr('name');
    }

    public function getTitleAttribute()
    {
        return $this->getAttr('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->getAttr('description');
    }

    public function getMetaTitleAttribute()
    {
        return $this->getAttr('meta_title');
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->getAttr('meta_description');
    }

    public function getMetaKeywordAttribute()
    {
        return $this->getAttr('meta_keyword');
    }

    public function getMetaH1Attribute()
    {
        return $this->getAttr('meta_h1');
    }

    public function getExtraH1Attribute()
    {
        return $this->getAttr('extra_h1');
    }


    //category_description setters

    public function setNameAttribute($value)
    {
        $this->setAttr('name', $value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->setAttr('description', $value);
    }

    public function setMetaTitleAttribute($value)
    {
        $this->setAttr('meta_title', $value);
    }

    public function setMetaDescriptionAttribute($value)
    {
        $this->setAttr('meta_description', $value);
    }

    public function setMetaKeywordAttribute($value)
    {
        $this->setAttr('meta_keyword', $value);
    }

    public function setMetaH1Attribute($value)
    {
        $this->setAttr('meta_h1', $value);
    }

    public function setExtraH1Attribute($value)
    {
        $this->setAttr('extra_h1', $value);
    }



    public function getFilterAttributes($show_in_card_only = false)
    {
        // Получаем attribute_id всех записей в отношениях filter_attributes для текущей категории
        $attributes = $this->filter_attributes()
            ->orderBy('oc_category_filter_attributes.sort_order');

        if ($show_in_card_only) {
            $attributes = $attributes->where('show_in_card', 1);
        }
        $attributes = $attributes->get();


        // Если атрибуты найдены, возвращаем их
        if (count($attributes)) {
            return $attributes;
        }

        // Если не найдены, проверяем родительскую категорию
        $parent = $this->parent;
        while ($parent) {
            $attributes = $parent->filter_attributes()
                ->orderBy('oc_category_filter_attributes.sort_order');
            if ($show_in_card_only) {
                $attributes = $attributes->where('show_in_card', 1);
            }
            $attributes = $attributes->get();
            if (!empty($attributes)) {
                return $attributes;
            }
            $parent = $parent->parent;
        }

        // Если у родительских категорий тоже нет атрибутов
        return Attribute::whereIn('attribute_id', [0])->get(); //12, 13, 14, 15, 28, 221, 933,
    }

    public function getUrlAttribute() {
        return $this->getUrl();
    }

    public function getUrl()
    {
        // Накопитель для части URL, чтобы обеспечить вложенность
        $slugs = [];
        $category = $this;

        // Проходим по всем родительским категориям, чтобы собрать все слаги
        while ($category) {
            // Получаем SEO URL для текущей категории из таблицы oc_seo_url
            $seoUrl = DB::table('oc_seo_url')
                ->where('query', 'category_id=' . $category->category_id)
                ->value('keyword');

            if ($seoUrl) {
                $slugs[] = $seoUrl;
            } else {
                // Если SEO URL нет, используем идентификатор категории в качестве запасного варианта
                $slugs[] = (string) $category->category_id;
            }

            // Переходим к следующему родителю
            $category = Category::find($category->parent_id);
        }

        // Переворачиваем массив, так как начинали с дочерних категорий, и формируем URL
        $slugs = array_reverse($slugs);
        $urlPath = implode('/', $slugs).'/';
        
        return $urlPath;
    }

}
