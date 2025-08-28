<?php

namespace Alexbeat\Electro\Classes;

use Cms\Classes\ComponentBase;
use Cms\Classes\Controller;
use Alexbeat\Electro\Models\Product;
use Alexbeat\Electro\Models\Attribute as AttributeModel;
use Alexbeat\Electro\Models\Manufacturer;
use Alexbeat\Electro\Models\ProductAttribute;
use Alexbeat\Electro\Classes\Pagination;
use Alexbeat\Electro\Models\Category;
use Alexbeat\Electro\Models\ProductDiscount;
use Alexbeat\Electro\Models\ProductSpecial;
use Alexbeat\Electro\Models\Store;

class CatalogController extends Controller
{
    public function list()
    {   
        define('DB_PREFIX','oc_');

        $category_id = input('category_id');
        if (!$category_id) {
            //return 404
            abort(404);
        }

        $store_id = input('store_id', 0);
        $customer_group_id = input('customer_group_id', 9);

        //get all children and itself of category
        $category_ids = Category::where('parent_id', $category_id)->orWhere('category_id', $category_id)->pluck('category_id')->toArray();

        $products_query = Product::with(['description', 'atributy'])
            ->whereHas('product_to_store', function ($query) use ($store_id) {
                $query->where('store_id', $store_id);
            })
            // ->inCategories([(int)$category_id])
            ->whereHas('categories', function ($query) use ($category_ids) {
                $query->whereIn('oc_product_to_category.category_id', $category_ids);
            })
        ;

        // $products_query->addSelectRaw("
        // (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . $customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, 
        // (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . $customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special
        // ");


        // $products_query->dd();

        //  общее кол-во товаров в категории
        $total_products_count = $products_query->count();

        // готовим контент фильтра
        $filter_content = $this->prepare_filter($products_query);

        // фильтр по наличию
        if (input('in_stock')) {
            $products_query->where('quantity', '>', 1);
        }

        // фильтр по производителю
        if (input('manufacturers')) {
            $manufacturers = explode(',', input('manufacturers'));
            $products_query->whereIn('manufacturer_id', $manufacturers);
        }

        // фильтр по цене
        if (input('price_from')) {
            $products_query->where('price', '>=', input('price_from'));
        }

        if (input('price_to')) {
            $products_query->where('price', '<=', input('price_to'));
        }

        // проход по input-параметрам для фильтрации по атрибутам
        foreach (request()->all() as $key => $value) {
            if (preg_match('/attr(\d+)_from/', $key, $matches)) {
                $nn = $matches[1];
                // if ($nn != 12) continue;
                $from = (float) input("attr{$nn}_from");
                $products_query->whereHas('atributy', function ($query) use ($nn, $from) {
                    $query->where('oc_product_attribute.attribute_id', $nn)
                        ->whereRaw('CAST(text AS DECIMAL) >= ?', [$from]);
                });
            }

            if (preg_match('/attr(\d+)_to/', $key, $matches)) {
                $nn = $matches[1];
                // if ($nn != 12) continue;
                $to = (float) input("attr{$nn}_to");
                $products_query->whereHas('atributy', function ($query) use ($nn, $to) {
                    $query->where('oc_product_attribute.attribute_id', $nn)
                        ->whereRaw('CAST(text AS DECIMAL) <= ?', [$to]);
                });
            }

            if (preg_match('/attr(\d+)/', $key, $matches)) {
                $nn = $matches[1];
                if (!is_array(input("attr{$nn}"))) continue;

                $list_values = (array)input("attr{$nn}");
                // print_r($list_values);
                $products_query->whereHas('atributy', function ($query) use ($nn, $list_values) {
                    $query->where('oc_product_attribute.attribute_id', $nn)
                        ->whereIn('text', $list_values);
                });
            }
        }

        $filtered_products_count = $products_query->count();


        // сортировка
        if ($sort = input('sort')) {
            $order = input('order');

            $sortMapping = [
                'p.price' => 'price',
                'p.hit' => 'hit',
                'p.date_added' => 'date_added',
                'p.rating' => 'rating',
                'p.sort_order' => 'sort_order',
            ];

            if (array_key_exists($sort, $sortMapping)) {
                $sort = $sortMapping[$sort];
            }

            // Предполагается, что в $order могут быть только 'asc' или 'desc'
            $method = $order === 'DESC' ? 'orderByDesc' : 'orderBy';
            $products_query->$method($sort);
        }



        // пагинация
        $page = input('page', 1);
        $per_page = $page == 1 ? 19 : 20;


        $url = input('url');

        //разобрать и добавить в url все параметры атрибутов, сортировки, страницы, цены
        // Сбор параметров
        $params = [];
        // $params['page'] = $page;
        if ($sort = input('sort')) {
            $params['sort'] = $sort;
            if ($order = input('order')) {
                $params['order'] = $order;
            }
        }
        if (input('price_from')) $params['price_from'] = input('price_from');
        if (input('price_to')) $params['price_to'] = input('price_to');
        if (input('in_stock')) $params['in_stock'] = input('in_stock');
        if (input('manufacturers')) $params['manufacturers'] = input('manufacturers');

        // Атрибуты
        foreach (request()->all() as $key => $value) {
            if (preg_match('/^attr\d+(_from|_to)?$/', $key)) {
                $params[$key] = $value;
            }
        }

        $url = input('url');
        $finalUrl = $this->buildUrlWithParams($url, $params);

        $products_query->addSelect([
            '*',
            'discount' => ProductDiscount::select('price')
                ->whereColumn('oc_product_discount.product_id', 'oc_product.product_id')
                ->where('customer_group_id', $customer_group_id)
                ->where('quantity', '1')
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

        $products_query = $products_query->paginate($per_page);





        $pagination = new Pagination();
        $pagination->total = $filtered_products_count;
        $pagination->page = $page;
        $pagination->limit = $per_page;
        $pagination->shown = $per_page;
        $pagination->url = $finalUrl;

        $list_content = $this->renderPartial('category', [
            'products' => $products_query,
            'category_id' => $category_id,
            'page' => $page,
            'url' => $url,
            'fias_telegram' => input('fias_telegram'),
            'fias_whatsapp' => input('fias_whatsapp'),
            'fias_phone_main' => input('fias_phone_main'),
            'fias_contact_mail' => input('fias_contact_mail'),
            'store_id' => $store_id,
            'customer_group_id' => $customer_group_id,
        ]);

        // $pagination_content = $this->renderPartial('category_pagination', [
        //     'products' => $products_query,
        // ]);

        $pagination_content = $pagination->render();

        return json_encode([
            'list' => $list_content,
            'filter' => $filter_content,
            'pagination' => $pagination_content,
            'total_products_count' => $total_products_count,
            'filtered_products_count' => $filtered_products_count,
        ]);
    }



    private function prepare_filter($products_query)
    {
        $productIds = $products_query->get()->pluck('product_id');

        $attributeIds = [
            12, 13, 14, 15, 28, 221, 933,
        ];

        $slider_attributes = [
            12, 13, 15, 221, 933,
        ];

        $checkboxes_attributes = [
            28, 14,
        ];

        $switch_attributes = [];

        $atributy = AttributeModel::whereIn('attribute_id', $attributeIds)->get();

        $atributy->each(function ($atribut) use ($productIds, $slider_attributes, $checkboxes_attributes, $switch_attributes) {
            $pa_query = ProductAttribute::query()
                ->where('attribute_id', $atribut->attribute_id)
                ->whereIn('product_id', $productIds);

            if (in_array($atribut->attribute_id, $slider_attributes)) {
                $atribut->type = 'slide';

                // Преобразовываем текстовые данные в числа для поиска min и max
                $atribut->min = $pa_query->selectRaw('MIN(CAST(text AS DECIMAL)) as min_value')->value('min_value');
                $atribut->max = $pa_query->selectRaw('MAX(CAST(text AS DECIMAL)) as max_value')->value('max_value');
                $atribut->from = input('attr' . $atribut->attribute_id . '_from', $atribut->min);
                $atribut->to = input('attr' . $atribut->attribute_id . '_to', $atribut->max);
            }

            if (in_array($atribut->attribute_id, $checkboxes_attributes)) {
                $atribut->type = 'checkboxes';

                // Правильное использование distinct и select
                $uniqueTextsQuery = $pa_query->select('text')->distinct();
                $atribut->selected_count = $uniqueTextsQuery->count();
                // echo $atribut->selected_count;

                // Получение уникальных значений
                $atribut->values = $uniqueTextsQuery->take(20)->get(); //берем макс. 20

                $search_values = (array)input('attr' . $atribut->attribute_id);
                // print_r($search_values);

                $selected_count = 0;
                $atribut->values->each(function ($value) use ($search_values, &$selected_count) {
                    if (in_array($value->text, $search_values)) {
                        $value->checked = true;
                        $selected_count++;
                    } else {
                        $value->checked = false;
                    }
                });
                $atribut->selected_count = $selected_count;

                // print_r($atribut->values->toArray());
            }

            if (in_array($atribut->attribute_id, $switch_attributes)) $atribut->type = 'switch';
        });



        $manufacturerIds = $products_query->distinct()->pluck('manufacturer_id');
        $manufacturers = Manufacturer::whereIn('manufacturer_id', $manufacturerIds)->orderBy('name')->get();
        $search_manufacturers = explode(',', input('manufacturers'));

        $manufacturers_checked_count = 0;
        $manufacturers->each(function ($manufacturer) use ($search_manufacturers, &$manufacturers_checked_count) {
            if (in_array($manufacturer->manufacturer_id, $search_manufacturers)) {
                $manufacturer->checked = true;
                $manufacturers_checked_count++;
            }
        });

        $price_min = (int)$products_query->min('price');
        $price_max = (int)$products_query->max('price');



        $data = [];
        $data['sort'] = input('sort', 'p.hit');
        $data['order'] = input('order', 'desc');

        // Массив с опциями сортировки
        $data['sort_options'] = [
            [
                'title' => 'По рейтингу',
                'sort'  => 'p.rating',
                'order' => 'DESC'
            ],
            [
                'title' => 'Сначала дешевле',
                'sort'  => 'p.price',
                'order' => 'ASC'
            ],
            [
                'title' => 'Сначала дороже',
                'sort'  => 'p.price',
                'order' => 'DESC'
            ],
            [
                'title' => 'Сначала новинки',
                'sort'  => 'p.date_added',
                'order' => 'DESC'
            ],
            [
                'title' => 'Сначала популярные',
                'sort'  => 'p.hit',
                'order' => 'DESC'
            ],
        ];

        // Установить sort_title на основе выбора сортировки и порядка
        $data['sort_title'] = '';
        foreach ($data['sort_options'] as $option) {
            if ($data['sort'] === $option['sort'] && $data['order'] === $option['order']) {
                $data['sort_title'] = $option['title'];
                break;
            }
        }

        $filter = [
            'atributy' => $atributy,
            'manufacturers' => $manufacturers,
            'manufacturers_checked_count' => $manufacturers_checked_count,
            'in_stock' => input('in_stock'),
            'sort_title' => $data['sort_title'],
            'sort_options' => $data['sort_options'],
            'sort' => $data['sort'],
            'order' => $data['order'],
            'price_from' => input('price_from'),
            'price_to' => input('price_to'),
            'price_min' => $price_min,
            'price_max' => $price_max,
        ];

        $filter_content = $this->renderPartial('category_filter', [
            'filter' => $filter,
        ]);

        return $filter_content;
    }


    protected function buildUrlWithParams($baseUrl, $params)
    {
        $baseUrl = html_entity_decode(urldecode($baseUrl));
        // trace_log($baseUrl);

        $parsedUrl = parse_url($baseUrl);

        // Существующие параметры
        $query = [];
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $query);
        }

        // Объединяем с новыми параметрами
        $query = array_merge($query, $params);

        // Собираем query string
        $queryString = http_build_query($query);

        // Собираем итоговый URL
        $url = $parsedUrl['path'] ?? '';
        if ($queryString) {
        }
        $url .= '?' . $queryString;
        $url = html_entity_decode(urldecode($url));

        // trace_log('result:' . $url);

        return $url;
    }
}
