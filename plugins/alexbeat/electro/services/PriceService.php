<?php

namespace Alexbeat\Electro\Services;
use Carbon\Carbon;

class PriceService
{
    public function __construct()
    {
    }

    public function getCashPrice($value)
    {
        $discount = 1 - 0.11;
        return ceil(($value * $discount) / 50) * 50;
    }

    public function isCashAvailable($city_id) {
        // $this->load->model('tool/city');
        // return $this->model_tool_city->isMainCity($city_id);
        return true;
    }    
}