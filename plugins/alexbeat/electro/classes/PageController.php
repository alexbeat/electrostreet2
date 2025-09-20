<?php

namespace Alexbeat\Electro\Classes;

use Cms\Classes\Controller;
use Alexbeat\Electro\Models\LegalsPage;
use Input;

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


}
