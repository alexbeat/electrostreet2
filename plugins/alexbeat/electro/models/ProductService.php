<?php

namespace Alexbeat\Electro\Models;

use Model;

/**
 * Model
 */
class ProductService extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var bool timestamps are disabled.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'alexbeat_electro_products_services';

    /**
     * @var array rules for validation.
     */
    public $rules = [];

    public $belongsTo = [
        'product' => ['Alexbeat\Electro\Models\Product', 'key' => 'product_id'],
        'service_product' => ['Alexbeat\Electro\Models\ProductDescription', 'key' => 'service_product_id'],
        'customer_group' => ['Alexbeat\Electro\Models\CustomerGroup', 'key' => 'customer_group_id'],
        'customer_group_description' => ['Alexbeat\Electro\Models\CustomerGroupDescription', 'key' => 'customer_group_id'],        
    ];

}
