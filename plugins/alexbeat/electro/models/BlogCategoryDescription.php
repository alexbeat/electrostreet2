<?php

namespace Alexbeat\Electro\Models;

use Model;
use DB;

class BlogCategoryDescription extends Model
{
    public $table = 'oc_blog_category_description';
    public $primaryKey = 'blog_category_id';
    public $incrementing = false;
    public $timestamps = false;
}