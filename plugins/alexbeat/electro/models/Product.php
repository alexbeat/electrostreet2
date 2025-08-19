<?php

namespace Alexbeat\Electro\Models;

use Model;

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

    // public $belongsTo = [
    //     'exponenta' => [
    //         'Alexbeat\Azoback\Models\Exponenta',
    //         'table' => 'exponentas',
    //     ],
    // ];

    // public function scopeRootCategories($query)
    // {
    //     return $query->where('parent_id', -1);
    // }

    // public function scopeSubCategories($query, $rootCategories)
    // {
    //     return $query->whereIn('parent_id', $rootCategories);
    // }

    // public function getRootCategoryOptions()
    // {
    //     return Category::rootCategories()->pluck('title', 'id')->all();
    // }

    // public function getSubCategoryOptions()
    // {
    //     return Category::subCategories(input('root_categories'))->pluck('title', 'id')->all();
    // }
}
