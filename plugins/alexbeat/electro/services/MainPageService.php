<?php

namespace Alexbeat\Electro\Services;

use Alexbeat\Electro\Models\MainPage;
use Alexbeat\Electro\Models\Product;
use Alexbeat\Electro\Models\Category;

class MainPageService
{
    public $data, $model;

    public function __construct()
    {
        $this->model = MainPage::class;
        $this->data = $this->model::instance();    
    }

    public function getIdealTransportFilterParams($category = null) {
        $category_service = new CategoryService();
        
        $items = $this->data->get('ideal_transport_categories');
        if (!$category) {
            $category = Category::find($items[0]['category']);
        }

        $filter_params = $category_service->getFilterParams($category, null, true);

        return $filter_params;
    }

}