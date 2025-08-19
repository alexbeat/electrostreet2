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
}