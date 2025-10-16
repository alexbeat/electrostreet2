<?php

namespace Alexbeat\Electro\Models;

use Model;

class CustomerGroup extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $table = 'oc_customer_group';
    protected $primaryKey = 'customer_group_id';
    public $incrementing = true;
    public $timestamps = false;

    public $rules = [];

    public $hasOne = [
        // 'description' => [CustomerGroupDescription::class, 'key' => 'customer_group_id', 'otherKey' => 'customer_group_id'],
        'customer_group_description' => [CustomerGroupDescription::class, 'key' => 'customer_group_id', 'otherKey' => 'customer_group_id'],
    ];

    public function getTitleAttribute()
    {
        return $this->customer_group_description->name;
    }
}
