<?php

namespace Alexbeat\Electro\Models;

use Model;
use DB;

class BlogCategory extends Model
{
    public $table = 'oc_blog_category';
    public $primaryKey = 'blog_category_id';
    public $incrementing = true;
    public $timestamps = false;

    public $hasOne = [
        'blog_category_description' => [
            'Alexbeat\Electro\Models\BlogCategoryDescription',
            'key' => 'blog_category_id',
            'otherKey' => 'blog_category_id',
            'conditions' => 'language_id = 1'
        ],
    ];
}