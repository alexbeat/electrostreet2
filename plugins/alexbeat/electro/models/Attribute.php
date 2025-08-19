<?php namespace Alexbeat\Electro\Models;

use Model;

class Attribute extends Model
{
    public $table = 'oc_attribute';
    public $primaryKey = 'attribute_id';
    public $timestamps = false;

    public $hasOne = [
        'description' => [
            'Alexbeat\Electro\Models\AttributeDescription',
            'key' => 'attribute_id',
            'otherKey' => 'attribute_id'
        ]
    ];

    public $belongsTo = [
        'group' => [
            'Alexbeat\Electro\Models\AttributeGroup',
            'key' => 'attribute_group_id'
        ]
    ];

    public function getNameAttribute() {
        return $this->description->name;
    }

    public function getTextAttribute() {
        return $this->pivot->text;
    }    
}