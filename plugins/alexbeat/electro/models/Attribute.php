<?php

namespace Alexbeat\Electro\Models;

use Model;

class Attribute extends Model
{
    use \October\Rain\Database\Traits\Sortable;

    public $table = 'oc_attribute';
    public $primaryKey = 'attribute_id';
    public $timestamps = false;

    public $hasOne = [
        'attribute_description' => [
            'Alexbeat\Electro\Models\AttributeDescription',
            'key' => 'attribute_id',
            'otherKey' => 'attribute_id'
        ]
    ];

    public $belongsTo = [
        'attribute_group' => [
            'Alexbeat\Electro\Models\AttributeGroup',
            'key' => 'attribute_group_id'
        ],
    ];

    public $belongsToMany = [
        'categories' => [
            'Alexbeat\Electro\Models\Category',
            'key' => 'category_id',
            'otherKey' => 'attribute_id',
            'table' => 'oc_category_filter_attributes',
            'pivot' => [
                'sort_order'
            ],
        ]
    ];

    public function getNameAttribute() {
        return $this->attribute_description ? $this->attribute_description->name : null;
    }

    public function setNameAttribute($value) {
        $this->attribute_description->name = $value;
        $this->attribute_description->save();
    }

    public function getTextAttribute()
    {
        return $this->pivot->text;
    }
}
