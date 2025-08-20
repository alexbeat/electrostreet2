<?php

namespace Alexbeat\Electro\Classes;

use Cms\Classes\ComponentBase;
use Cms\Classes\Controller;
use Alexbeat\Electro\Models\Product;
use Alexbeat\Electro\Models\Attribute as AttributeModel;
use Alexbeat\Electro\Models\Manufacturer;
use Alexbeat\Electro\Models\ProductAttribute;

class CatalogController extends Controller
{
    public function list()
    {
        $products_query = Product::query()->with('description', 'atributy');

        // фильтр категории
        $products_query = $products_query->inCategories([740]);


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
        // if (input('price_from')) {
        //     $products_query->where('price', '>=', input('price_from'));
        // }

        // if (input('price_to')) {
        //     $products_query->where('price', '<=', input('price_to'));
        // }

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




        // сортировка
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


        // пагинация
        $products_query = $products_query->paginate();

        // print_r($products_query->get()->toArray());

        $list_content = $this->renderPartial('category', [
            'products' => $products_query,
            'page' => input('page', 1),
            'fias_telegram' => '',
            'fias_whatsapp' => '',
            'fias_phone_main' => '',
            'fias_contact_mail' => '',
        ]);

        $pagination_content = $this->renderPartial('category_pagination', [
            'products' => $products_query,
        ]);

        return json_encode([
            'list' => $list_content,
            'filter' => $filter_content,
            'pagination' => $pagination_content,
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

        $price_min = $products_query->min('price');
        $price_max = $products_query->max('price');



        $data = [];
        $data['sort'] = input('sort', 'popular');
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
}
