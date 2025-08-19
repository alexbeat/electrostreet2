<?php

namespace Alexbeat\Electro\Classes;

use Cms\Classes\ComponentBase;
use Cms\Classes\Controller;
use Alexbeat\Electro\Components\Catalog;
use Alexbeat\Electro\Models\Product;

class CatalogController extends Controller
{
    public function list()
    {
        $products_query = Product::query();
        // print_r($products->toArray());

        // $products_query = $products_query->where('hit', 1);

        $products_query = $products_query->inCategories([740]);

        if (input('in_stock')) {
            $products_query->where('quantity', '>', 1);
        }

        if (input('manufacturers')) {
            $manufacturers = explode(',', input('manufacturers'));
            $products_query->whereIn('manufacturer_id', $manufacturers);
        }


        if ($sort = input('sort')) {
            $order = input('order');

            $sortMapping = [
                'p.price' => 'price',
                'p.hit' => 'hit',
                'p.date_added' => 'date_added',
                'p.rating' => 'rating'
            ];

            if (array_key_exists($sort, $sortMapping)) {
                $sort = $sortMapping[$sort];
            }

            // Предполагается, что в $order могут быть только 'asc' или 'desc'
            $method = $order === 'DESC' ? 'orderByDesc' : 'orderBy';
            $products_query->$method($sort);
        }



        $products_query = $products_query->paginate();



        $list_content = $this->renderPartial('category', [
            'products' => $products_query
        ]);

        $filter_content = $this->renderPartial('category_filter', [
            'products' => $products_query
        ]);

        $filter_content = $this->renderPartial('category_pagination', [
            'products' => $products_query
        ]);

        return json_encode([
            'list' => $list_content,
            'filter' => $filter_content,
            'pagination' => $pagination_content,
        ]);
    }

    private function prepare_filter()
    {
    }
}
