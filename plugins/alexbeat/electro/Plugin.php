<?php namespace Alexbeat\Electro;

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
    }

    public function registerMarkupTags() {
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
            ]
        ];
    }
}
