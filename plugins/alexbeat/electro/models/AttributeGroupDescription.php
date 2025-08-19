<?php namespace Alexbeat\Electro\Models;

use Model;

class AttributeGroupDescription extends Model
{
    public $table = 'oc_attribute_group_description';
    public $primaryKey = null;
    public $timestamps = false;
    public $incrementing = false;

    public $belongsTo = [
        'group' => [
            'Alexbeat\Electro\Models\AttributeGroup',
            'key' => 'attribute_group_id'
        ]
    ];
}