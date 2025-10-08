<?php

namespace Alexbeat\Electro\Models;

use Model;
use DB;

class BlogPost extends Model
{
    public $table = 'oc_blog';
    public $primaryKey = 'blog_id';
    public $incrementing = true;
    public $timestamps = false;

    public $hasOne = [
        'blogpost_description' => [
            'Alexbeat\Electro\Models\BlogPostDescription',
            'key' => 'blog_id',
            'otherKey' => 'blog_id',
            'conditions' => 'language_id = 1'
        ],
    ];

    public $belongsToMany = [
        'categories' => [
            'Alexbeat\Electro\Models\BlogCategory',
            'table' => 'oc_blog_to_category',
            'key' => 'blog_id',
            'otherKey' => 'blog_category_id',
        ],
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeNotVideo($query) {
        return $query->whereHas('categories', function ($query) {
            $query->where('oc_blog_to_category.blog_category_id', '!=', 5);
        });
    }

    public function getTitleAttribute()
    {
        return $this->blogpost_description->title;
    }

    public function getShortDescriptionAttribute()
    {
        return $this->blogpost_description->short_description;
    }

    public function getUrlAttribute()
    {
        $seoUrl = DB::table('oc_seo_url')
            ->where('query', 'blog_id=' . $this->blog_id)
            ->value('keyword');
        return '/' . $seoUrl . '/';
    }
}
