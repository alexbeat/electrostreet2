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

    public $belongsToMany = [
        'categories' => [
            'Alexbeat\Electro\Models\Category',
            'table' => 'oc_product_to_category',
            'key' => 'product_id',
            'otherKey' => 'category_id',
            'pivot' => ['product_id', 'category_id', 'main_category'],
        ]
    ];



    public function getThumbAttribute()
    {
        return ResizeImages::resize(\Storage::disk('media')->url($this->image), 200, 200, ['mode' => 'crop']);
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

    // public function scopeInCategories($query, $categories) {
    //     return $query->whereIn('category_id', $categories);
    // }

    // public function scopeInCategories($query, array $categoryIds)
    // {
    //     $categoryModel = new Category();
    //     $allCategoryIds = $categoryModel->getAllSubCategories($categoryIds);

    //     return $query->whereIn('product_id', function($query) use ($allCategoryIds) {
    //         $query->select('product_id')
    //             ->from('oc_product_to_category')
    //             ->whereIn('category_id', $allCategoryIds);
    //     });
    // }    


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
        return $query->whereHas('categories', function($q) use ($allCategoryIds) {
            $q->whereIn('oc_product_to_category.category_id', $allCategoryIds);
        });
    }



}
