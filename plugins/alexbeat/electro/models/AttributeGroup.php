<?php namespace Alexbeat\Electro\Models;

use Model;

class AttributeGroup extends Model
{
    public $table = 'oc_attribute_group';
    public $primaryKey = 'attribute_group_id';
    public $timestamps = false;

    public $hasMany = [
        'descriptions' => [
            'Alexbeat\Electro\Models\AttributeGroupDescription',
            'key' => 'attribute_group_id',
            'otherKey' => 'attribute_group_id'
        ]
    ];
}