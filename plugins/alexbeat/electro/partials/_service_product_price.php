<?php

use Alexbeat\Electro\Models\Product;
use Alexbeat\Electro\Models\ProductDiscount;
use Alexbeat\Electro\Models\ProductSpecial;

$customer_group_id = $record->customer_group_id;

$products_query = Product::where('product_id', $record->service_product_id);

$products_query->addSelect([
    '*',
    'discount' => ProductDiscount::select('price')
        ->whereColumn('oc_product_discount.product_id', 'oc_product.product_id')
        ->where('customer_group_id', $customer_group_id)
        ->where('quantity', '>=', '1')
        ->whereRaw("((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW()))")
        ->orderBy('priority', 'ASC')
        ->orderBy('price', 'ASC')
        ->limit(1)
]);

$products_query->addSelect([
    'special' => ProductSpecial::select('price')
        ->whereColumn('oc_product_special.product_id', 'oc_product.product_id')
        ->where('customer_group_id', $customer_group_id)
        ->whereRaw("((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW()))")
        ->orderBy('priority', 'ASC')
        ->orderBy('price', 'ASC')
        ->limit(1)
]);

$service_product = $products_query->first();

$service_product->price = $service_product->special ? $service_product->special : ($service_product->discount ? $service_product->discount : $service_product->price);

echo ' ' . $service_product->price;// . '=' . $service_product->discount . '=' . $service_product->special;
