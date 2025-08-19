<?php

namespace Alexbeat\Electro\Components;

use Alexbeat\Electro\Models\Product;
use Cms\Classes\ComponentBase;

class Catalog extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Catalog Component',
            'description' => 'Отображение каталога и его составляющих'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        // // $this->page['currentSort'] = input('sort', 'popular');
        // $this->page['filter_params'] = \Input::all();

        $this->page['products'] = Product::paginate();

        // return json_encode(['list' => $this->page['products']->toArray()]);
    }

    public function getItems()
    {
        return Product::paginate();
    }
}
