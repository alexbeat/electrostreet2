<?php namespace Alexbeat\Electro\Models;

use Model;

class Manufacturer extends Model
{
    public $table = 'oc_manufacturer';
    public $primaryKey = 'manufacturer_id';
    public $timestamps = false;

    // Связь с описаниями (например, для мультиязычности)
    public $hasOne = [
        'description' => [
            'Alexbeat\Electro\Models\ManufacturerDescription',
            'key' => 'manufacturer_id',
            'otherKey' => 'manufacturer_id'
        ]
    ];

    // Если есть связь с товарами
    // public $hasManyThrough = [
    //     'products' => [
    //         'Alexbeat\Electro\Models\Product',
    //         'through' => 'Alexbeat\Electro\Models\ProductToManufacturer',
    //         'key' => 'manufacturer_id',
    //         'otherKey' => 'product_id'
    //     ]
    // ];
}