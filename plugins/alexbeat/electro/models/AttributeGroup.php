<?php

namespace Alexbeat\Electro\Models;

use Model;

class AttributeGroup extends Model
{
    use \October\Rain\Database\Traits\Sortable;

    public $table = 'oc_attribute_group';
    public $primaryKey = 'attribute_group_id';
    public $timestamps = false;

    public $sortable = 'sort_order';

    public $hasOne = [
        'description' => [
            'Alexbeat\Electro\Models\AttributeGroupDescription',
            'key' => 'attribute_group_id',
            'otherKey' => 'attribute_group_id',
            'conditions' => 'language_id = 1',
            'delete' => true,
        ]
    ];

    public $hasMany = [
        'group_attributes' => [
            'Alexbeat\Electro\Models\Attribute',
            'key' => 'attribute_group_id',
            // 'otherKey' => 'attribute_group_id',
        ]
    ];

    public function afterCreate()
    {
        $data = post('AttributeGroup');
        if (isset($data['description'])) {
            $desc = $data['description'];
            $this->description()->create([
                'name' => $desc['name'] ?? '',
                'language_id' => $desc['language_id'] ?? 1,
            ]);
        }
    }

}
