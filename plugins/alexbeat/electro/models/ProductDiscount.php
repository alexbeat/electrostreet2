<?php namespace Alexbeat\Electro\Models;

use Model;

class ProductDiscount extends Model
{
    public $table = 'oc_product_discount';
    public $primaryKey = 'product_discount_id';
    public $timestamps = false;

    public $belongsTo = [
        'customer_group' => [
            CustomerGroup::class, 
            // 'order' => 'name asc',
        ],
    ];

}