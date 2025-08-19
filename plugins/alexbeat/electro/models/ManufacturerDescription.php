<?php

namespace Alexbeat\Electro\Models;

use Model;

class ManufacturerDescription extends Model
{
    public $primaryKey = null;
    public $table = 'oc_manufacturer_description';
    public $timestamps = false;
    public $incrementing = false;

    public $belongsTo = [
        'manufacturer' => [
            'Alexbeat\Electro\Models\Manufacturer',
            'key' => 'manufacturer_id'
        ]
    ];
}
