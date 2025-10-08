<?php

namespace Alexbeat\Electro\Services;

// use Alexbeat\Electro\Models\Category;
// use Alexbeat\Electro\Models\Attribute as AttributeModel;
use Alexbeat\Electro\Models\ProductAttribute;
use Alexbeat\Electro\Models\Manufacturer;
use Alexbeat\Electro\Models\Product;

class CategoryService
{
    public const CHECKBOX_TYPE = 0;
    public const RANGE_TYPE = 1;
    public const SWITCH_TYPE = 2;
    public const SORT_TEXT_ASC = 0;
    public const SORT_TEXT_DESC = 1;
    public const SORT_NUMERIC_ASC = 2;
    public const SORT_NUMERIC_DESC = 3;

    public $model;

    public function __construct()
    {

    }

    public function getFilterParams($category, $products_query, $only_card_params = false)
    {
        $filter_params_count = 0;

        if (!$products_query) {
            $products_query = Product::inCategories([(int)$category->category_id]);
        }

        $productIds = $products_query->get()->pluck('product_id');

        $atributy = $category->getFilterAttributes($only_card_params);//ОТЛИЧИЕ ОТ КЛАССА КОНТРОЛЛЕРА КАТАЛОГА

        $atributy->each(function ($atribut) use ($productIds, &$filter_params_count) {
            $pa_query = ProductAttribute::query()
                ->where('attribute_id', $atribut->attribute_id)
                ->whereIn('product_id', $productIds);

            // trace_log($atribut->name.' '.$atribut->attribute_id.' '.$atribut->filter_type_id);

            if ($atribut->filter_type_id == static::RANGE_TYPE) {
                $atribut->type = 'slide';

                // Преобразовываем текстовые данные в числа для поиска min и max
                // Игнорируем пустые значения при вычислении минимального значения
                $atribut->min = $pa_query
                    ->where('text', '!=', '')
                    ->selectRaw('MIN(CAST(text AS DECIMAL)) as min_value')
                    ->value('min_value');

                // Игнорируем пустые значения при вычислении максимального значения
                $atribut->max = $pa_query
                    ->where('text', '!=', '')
                    ->selectRaw('MAX(CAST(text AS DECIMAL)) as max_value')
                    ->value('max_value');

                $atribut->from = input('attr' . $atribut->attribute_id . '_from', $atribut->min);
                $atribut->to = input('attr' . $atribut->attribute_id . '_to', $atribut->max);
                if ($atribut->from != $atribut->min) {
                    $filter_params_count++;
                }
                if ($atribut->to != $atribut->max) {
                    $filter_params_count++;
                }
            }

            if ($atribut->filter_type_id == static::CHECKBOX_TYPE) {
                $atribut->type = 'checkboxes';

                // Правильное использование distinct и select
                $uniqueTextsQuery = $pa_query->select('text')->distinct()->where('text', '!=', '');
                $atribut->selected_count = $uniqueTextsQuery->count();
                // echo $atribut->selected_count;

                switch ($atribut->sort_type_id) {
                    case static::SORT_TEXT_DESC:
                        $uniqueTextsQuery->orderBy('text', 'desc');
                        break;
                    case static::SORT_NUMERIC_ASC:
                        $uniqueTextsQuery->orderByRaw('CAST(text AS DECIMAL)');
                        break;
                    case static::SORT_NUMERIC_DESC:
                        $uniqueTextsQuery->orderByRaw('CAST(text AS DECIMAL) DESC');
                    default:
                        $uniqueTextsQuery->orderBy('text', 'asc');
                }


                // Получение уникальных значений
                $atribut->values = $uniqueTextsQuery->take(30)->get(); //берем макс. 30

                $search_values = (array)input('attr' . $atribut->attribute_id);
                // $search_values = (array)explode(',', input('attr' . $atribut->attribute_id));
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
                if ($selected_count) $filter_params_count++;

                // print_r($atribut->values->toArray());
            }

            if ($atribut->filter_type_id == static::SWITCH_TYPE) $atribut->type = 'switch';
        });



        $manufacturerIds = $products_query->distinct()->pluck('manufacturer_id');
        $manufacturers = Manufacturer::whereIn('manufacturer_id', $manufacturerIds)->orderBy('name')->get();
        $search_manufacturers = explode(',', (string)input('manufacturers'));
        $manufacturers_checked_count = 0;
        $manufacturers->each(function ($manufacturer) use ($search_manufacturers, &$manufacturers_checked_count) {
            if (in_array($manufacturer->manufacturer_id, $search_manufacturers)) {
                $manufacturer->checked = true;
                $manufacturers_checked_count++;
            }
        });
        if ($manufacturers_checked_count) $filter_params_count++;

        $price_min = (int)$products_query->min('price');
        $price_max = (int)$products_query->max('price');



        $data = [];
        $data['sort'] = input('sort');
        $data['order'] = input('order');
        if ($data['sort'] || $data['order']) $filter_params_count++;

        // Массив с опциями сортировки
        $data['sort_options'] = [
            [
                'title' => 'По рейтингу',
                'sort'  => 'p.rating',
                'order' => 'DESC'
            ],
            [
                'title' => 'Сначала со скидкой',
                'sort'  => 'best_discount',
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
            'filters_count' => $filter_params_count,
            'choosed_category_url' => $category->url,//ОТЛИЧИЕ
            // 'choosed_category_id' => $category->id,//ОТЛИЧИЕ
        ];

        // $filter_content = $this->renderPartial('category_filter', [
        //     'filter' => $filter,
        // ]);

        // return $filter_content;

        return $filter;
    }


}