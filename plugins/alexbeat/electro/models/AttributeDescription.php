<?php namespace Alexbeat\Electro\Models;

use Model;

class AttributeDescription extends Model
{
    public $table = 'oc_attribute_description';
    public $primaryKey = 'attribute_id';
    public $timestamps = false;
    public $incrementing = false;

    public $belongsTo = [
        'attribute' => [
            'Alexbeat\Electro\Models\Attribute',
            'key' => 'attribute_id'
        ]
    ];
}