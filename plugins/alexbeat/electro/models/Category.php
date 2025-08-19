<?php namespace Alexbeat\Electro\Models;

use Model;

class Category extends Model
{
    public $table = 'oc_category';
    public $primaryKey = 'category_id';
    public $incrementing = true;
    public $timestamps = false;

    // Связи
    public $hasMany = [
        'descriptions' => [
            'Alexbeat\Electro\Models\CategoryDescription',
            'key' => 'category_id',
            'otherKey' => 'category_id'
        ],
        'stores' => [
            'Alexbeat\Electro\Models\CategoryToStore',
            'key' => 'category_id',
            'otherKey' => 'category_id'
        ],
        'paths' => [
            'Alexbeat\Electro\Models\CategoryPath',
            'key' => 'category_id',
        ],
            'otherKey' => 'category_id'
    ];

    public $belongsToMany = [
        'products' => [
            'Alexbeat\Electro\Models\Product',
            'table' => 'oc_product_to_category',
            'key' => 'category_id',
            'otherKey' => 'product_id',
            'pivot' => ['product_id', 'category_id', 'main_category'],
        ]
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


}