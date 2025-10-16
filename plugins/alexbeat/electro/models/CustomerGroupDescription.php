<?php

namespace Alexbeat\Electro\Models;

use Model;

class CustomerGroupDescription extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $table = 'oc_customer_group_description';
    protected $primaryKey = 'customer_group_id';
    public $incrementing = false;
    public $timestamps = false;

    public $rules = [];
}
