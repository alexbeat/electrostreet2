<?php namespace Alexbeat\Electro\Models;

use Model;

class CategoryDescription extends Model
{
    public $table = 'oc_category_description';
    public $primaryKey = 'category_id';
    public $timestamps = false;
    public $incrementing = false;

    public $belongsTo = [
        'category' => [
            'Alexbeat\Electro\Models\Category',
            'key' => 'category_id',
            'otherKey' => 'category_id'
        ]
    ];
}