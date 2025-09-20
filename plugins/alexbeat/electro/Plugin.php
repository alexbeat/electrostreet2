<?php

namespace Alexbeat\Electro;

use Alexbeat\Electro\Services\HelperService;
use System\Classes\PluginBase;

/**
 * Plugin class
 */
class Plugin extends PluginBase
{
    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        \Route::get('/api/catalog/list', 'Alexbeat\Electro\Classes\CatalogController@list');
        \Route::post('/api/catalog/list', 'Alexbeat\Electro\Classes\CatalogController@list');
        \Route::post('/api/faq/list', 'Alexbeat\Electro\Classes\FaqController@list');
        \Route::post('/api/page/legals', 'Alexbeat\Electro\Classes\PageController@legals');
        new HelperService();
    }

    /**
     * registerComponents used by the frontend.
     */

    public function registerComponents()
    {
        return [
            'Alexbeat\Electro\Components\Catalog' => 'catalog',
        ];
    }

    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
        return [
            'faq' => [
                'label' => 'Вопросы-ответы',
                'description' => 'Вопросы-ответы',
                'category' => 'Electro',
                'icon' => 'icon-cog',
                'order' => 10,
                'class' => \Alexbeat\Electro\Models\Faq::class,
            ],
            'legalspage' => [
                'label' => 'Юридическим лицам',
                'description' => 'Юридическим лицам',
                'category' => 'Electro',
                'icon' => 'icon-cog',
                'order' => 20,
                'class' => \Alexbeat\Electro\Models\LegalsPage::class,
            ]            
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'briefphone' => function ($value) {
                    return HelperService::briefphone($value);
                },

                'includes' => function ($array, $id) {
                    return in_array($id, $array);
                },

                'formatPrice' => function ($value, $forceZero = false) {
                    return HelperService::formatPrice($value, $forceZero);
                },

                'formatVideoUrl' => function ($url) {
                    return HelperService::formatVideoUrl($url);
                },

                'print_r' => function ($array) {
                    return print_r($array, 1);
                },

                'formatSpan' => function ($string) {
                    $result = str_replace('<<<', '<span>', $string);
                    $result = str_replace('>>>', '</span>', $result);
                    return $result;
                },
            ]
        ];
    }
}
