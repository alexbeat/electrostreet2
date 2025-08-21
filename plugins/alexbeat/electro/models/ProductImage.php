<?php

namespace Alexbeat\Electro\Models;

use Model;
use System\Classes\ResizeImages;

class ProductImage extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $table = 'oc_product_image';
    protected $primaryKey = 'product_id';
    public $incrementing = false;
    public $timestamps = false;

    public $rules = [];

    public $belongTo = [
        'product' => 'Alexbeat\Electro\Models\Product',
    ];

    public function getThumbAttribute()
    {
        return ResizeImages::resize(\Storage::disk('media')->url($this->image), 200, 200, ['mode' => 'crop']);
    }    
}