<?php

namespace Alexbeat\Electro\Classes;

use Cms\Classes\Controller;
use Alexbeat\Electro\Models\Faq;

class FaqController extends Controller
{
    public function list()
    {
        $faq = Faq::instance()->content;
        
        $faq_content = $this->renderPartial('faq', [
            'faq' => $faq,
        ]);        

        return json_encode([
            'list' => $faq_content,
        ]);
    }
}
