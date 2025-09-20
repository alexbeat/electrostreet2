<?php

namespace Alexbeat\Electro\Models;

use Model;
use System\Classes\ResizeImages;
use Alexbeat\Electro\Models\Category;

/**
 * Stand Model
 *
 * @link https://docs.octobercms.com/4.x/extend/system/models.html
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    protected $table = 'oc_product';
    protected $primaryKey = 'product_id';
    public $incrementing = true;
    public $timestamps = false;

    // protected $jsonable = ['invest_items', 'root_categories', 'sub_categories'];

    /**
     * @var array rules for validation
     */
    public $rules = [];

    public $hasOne = [
        'description' => ['Alexbeat\Electro\Models\ProductDescription', 'key' => 'product_id', 'otherKey' => 'product_id'],
    ];

    public $hasMany = [
        'images' => ['Alexbeat\Electro\Models\ProductImage', 'key' => 'product_id', 'otherKey' => 'product_id'],
        'product_to_store' => ['Alexbeat\Electro\Models\ProductToStore', 'key' => 'product_id', 'otherKey' => 'product_id'],
        'product_discount' => ['Alexbeat\Electro\Models\ProductDiscount', 'key' => 'product_id', ],
        'product_special' => ['Alexbeat\Electro\Models\ProductSpecial', 'key' => 'product_id', ],
    ];

    public $belongsToMany = [
        'categories' => [
            'Alexbeat\Electro\Models\Category',
            'table' => 'oc_product_to_category',
            'key' => 'product_id',
            'otherKey' => 'category_id',
            'pivot' => ['product_id', 'category_id', 'main_category'],
        ],

        'atributy' => [
            'Alexbeat\Electro\Models\Attribute',
            'table' => 'oc_product_attribute',
            'key' => 'product_id',
            'otherKey' => 'attribute_id',
            'pivot' => ['product_id', 'attribute_id', 'language_id', 'text'],
        ],

        'stores' => [
            'Alexbeat\Electro\Models\Store',
            'table' => 'oc_product_to_store',
            'key' => 'product_id',
            'otherKey' => 'store_id',
            'pivot' => ['product_id', 'store_id',],
        ],        
    ];
    

    public function getThumbAttribute()
    {
        return ResizeImages::resize(\Storage::disk('media')->url($this->image), 200, 200, ['mode' => 'fit']);
    }

    public function getNameAttribute()
    {
        return $this->description->name;
    }

    public function getSlug()
    {
        // Получаем идентификатор текущей модели
        $productId = $this->product_id;

        // Ищем запись в таблице oc_seo_url, где query = 'product_id=id', language = 1, store_id = 0
        $seoUrl = \DB::table('oc_seo_url')
            ->where('query', 'product_id=' . $productId)
            ->where('language_id', 1)
            ->where('store_id', 0)
            ->first();

        // Если запись найдена, возвращаем keyword, в противном случае возвращаем null или другой fallback
        return $seoUrl ? $seoUrl->keyword : null;
    }

    public function getHrefAttribute()
    {
        $slug = $this->getSlug();
        return $slug ? '/' . $slug . '/' : null;
    }

    public function getLimitedImagesAttribute() {
        return $this->images()->take(4)->get();
    }

    public function scopeInCategories($query, $categories)
    {
        // $allCategoryIds = [];

        // // Loop through each category to find all subcategories
        // foreach ($categories as $category) {
        //     $categoryModel = Category::find($category);
        //     if ($categoryModel) {
        //         $subCategoryIds = $categoryModel->getAllSubCategories($categories);
        //         $allCategoryIds = array_merge($allCategoryIds, $subCategoryIds);
        //     }
        // }

        // // Remove duplicates
        // $allCategoryIds = array_unique($allCategoryIds);

        // // Include the original categories as well
        // $allCategoryIds = array_merge($allCategoryIds, $categories);

        $category = new Category();

        $allCategoryIds = $category->getAllSubCategories($categories);

        // Filter products by these category ids
        return $query->whereHas('categories', function ($q) use ($allCategoryIds) {
            $q->whereIn('oc_product_to_category.category_id', $allCategoryIds);
        });
    }

    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
