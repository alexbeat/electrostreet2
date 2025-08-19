<?php namespace Alexbeat\Electro\Models;

use Model;

class CategoryToStore extends Model
{
    public $table = 'oc_category_to_store';
    public $primaryKey = null;
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