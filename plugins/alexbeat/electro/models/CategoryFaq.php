<?php namespace Alexbeat\Electro\Models;

use Model;

class CategoryFaq extends Model
{
    public $table = 'oc_category_faq';
    public $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;

    public $belongsTo = [
        'category' => [
            'Alexbeat\Electro\Models\Category',
            'key' => 'category_id',
            'otherKey' => 'category_id'
        ]
    ];
}