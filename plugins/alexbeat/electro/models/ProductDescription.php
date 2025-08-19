<?php

namespace Alexbeat\Electro\Models;

use Model;

class ProductDescription extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $table = 'oc_product_description';
    protected $primaryKey = 'product_id';

    public $rules = [];

    public $belongTo = [
        'product' => 'Alexbeat\Electro\Models\Product',
    ];
}