<?php

namespace Alexbeat\Electro\Models;

use DB;

class MainPage extends \System\Models\SettingModel
{
    public $settingsCode = 'alexbeat_electro_main_page';

    public $settingsFields = 'fields.yaml';

    // public $belongsToMany = [
    //     'sale_products' => [
    //         Product::class,
    //         'table' => 'alexbeat_electro_main_page_sale_products',
    //         // 'otherKey' => 'alexbeat_electro_main_page_sale_products.product_id',
    //         'otherKey' => 'product_id',
    //         'key'=>'id',
    //     ]
    // ];

    public function getCategoryOptions()
    {
        $categories = Category::with('category_description')->orderBy('category_id')->orderBy('parent_id')->get();

        // Формируем массив опций из коллекции
        $categoryOptions = [];
        foreach ($categories as $category) {
            if ($category->category_description) {
                $categoryOptions[$category->category_id] = $category->category_description->name . ' (' . $category->category_id . ')';
            }
        }

        return $categoryOptions;
    }

    // public function getProductOptions() {
    //     $products = Product::query()->orderBy('product_id')->lists('product_id','product_id');
    //     return $products;
    // }

    public function getProductOptions() {
        $productOptions = [];
    
        Product::with('description')->orderBy('product_id')->chunk(100, function ($products) use (&$productOptions) {
            foreach ($products as $product) {
                $productOptions[$product->product_id] = $product->description->name;
            }
        });
    
        return $productOptions;
    }
    
    // public function getProductOptions() {
    //     $productOptions = DB::table('oc_product')
    //         ->join('oc_product_description', 'oc_product.product_id', '=', 'oc_product_description.product_id')
    //         ->orderBy('oc_product.product_id')
    //         ->pluck('oc_product_description.name', 'oc_product.product_id');
    
    //     return $productOptions;
    // }


    
}
