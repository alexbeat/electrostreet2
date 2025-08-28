<?php namespace Alexbeat\Electro\Models;

use Model;

class ProductAttribute extends Model
{
    public $table = 'oc_product_attribute';
    public $primaryKey = 'id';
    public $timestamps = false;
    // public $incrementing = false;

    public $belongsTo = [
        'product' => [
            'Alexbeat\Electro\Models\Product',
            'key' => 'product_id'
        ],
        'attribute' => [
            'Alexbeat\Electro\Models\Attribute',
            'key' => 'attribute_id'
        ]
    ];
}