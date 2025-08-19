<?php namespace Alexbeat\Electro\Models;
use Model;

class CategoryPath extends Model
{
    public $table = 'oc_category_path';
    public $primaryKey = null;
    public $timestamps = false;
    public $incrementing = false;

    public $belongsTo = [
        'category' => [
            'Alexbeat\Electro\Models\Category',
            'key' => 'category_id',
            'otherKey' => 'category_id',
        ]
    ];
}