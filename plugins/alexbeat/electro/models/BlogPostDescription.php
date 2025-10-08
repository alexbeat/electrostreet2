<?php

namespace Alexbeat\Electro\Models;

use Model;

class BlogPostDescription extends Model
{
    public $table = 'oc_blog_description';
    public $primaryKey = 'blog_id';
    public $incrementing = false;
    public $timestamps = false;
}