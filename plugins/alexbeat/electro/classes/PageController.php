<?php

namespace Alexbeat\Electro\Classes;

use Alexbeat\Electro\Models\Category;
use Cms\Classes\Controller;
use Alexbeat\Electro\Models\LegalsPage;
use Alexbeat\Electro\Models\MainPage;
use Alexbeat\Electro\Services\CategoryService;
use Alexbeat\Electro\Services\MainPageService;

class PageController extends Controller
{
    public function legals()
    {
        $model = LegalsPage::class;
        $partial = 'legals';

        $data = $model::instance();
        
        $page_content = $this->renderPartial($partial, [
            'data' => $data,
            'fias_telegram' => input('fias_telegram'),
            'fias_whatsapp' => input('fias_whatsapp'),
            'fias_phone_main' => input('fias_phone_main'),
            'fias_contact_mail' => input('fias_contact_mail'),            
            'link_page' => input('link_page'),
            'module_id' => input('module_id'),
        ]);        

        return json_encode([
            'content' => $page_content,
        ]);
    }

    public function main()
    {
        $model = MainPage::class;
        $partial = 'main';

        $data = $model::instance();

        $page_content = $this->renderPartial($partial, [
            'data' => $data,
            'fias_telegram' => input('fias_telegram'),
            'fias_whatsapp' => input('fias_whatsapp'),
            'fias_phone_main' => input('fias_phone_main'),
            'fias_contact_mail' => input('fias_contact_mail'),
            'fias_max' => input('fias_max'),     
            'fias_open' => input('fias_open'),
            'fias_adres' => input('fias_adres'),    
            'city_pc' => input('city_pc'),
            'fias_map' => input('fias_map'),
            'shop_images' => input('shop_images'),
            'customer_group_id' => input('customer_group_id'),
        ]);        

        return json_encode([
            'content' => $page_content,
        ]);
    }

    public function mainFilter() {
        $category = Category::find(input('category_id'));

        $main_page_service = new MainPageService();
        
        $categories = MainPage::instance()->get('ideal_transport_categories');

        $choosed_category_title = '';
        foreach($categories as $cat) {
            if($cat['category'] == $category->category_id) {
                $choosed_category_title = $cat['title'];
                $choosed_category_id = $cat['category'];
            }
        }

        $filter_params = $main_page_service->getIdealTransportFilterParams($category);

        $filter_content = $this->renderPartial('main/ideal_transport_filter', [
            'filter' => $filter_params,
            'ideal_transport_categories' => MainPage::instance()->get('ideal_transport_categories'),    
            'choosed_category_title' => $choosed_category_title,
            'choosed_category_id' => $choosed_category_id,
        ]);

        return $filter_content;
    }

}
