<?php namespace Alexbeat\Electro\Models;

use Model;

class Store extends Model
{
    public $table = 'oc_store';
    public $primaryKey = 'store_id';
    public $timestamps = false;

    public $hasMany = [
        'products' => [
            'Alexbeat\Electro\Models\Product',
            'key' => 'product_id'
        ],
    ];

    public static function find($model_id) {
        if ($model_id == 0) {
            // static::$name = '123';
            // $this->url = '222';
            // $this->ssl = '444';
            // return $this;
        }
        return parent::find($model_id);
    }
}