<?php namespace Alexbeat\Electro;

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
        \Route::get('/test', 'Alexbeat\Electro\Classes\CatalogController@list');
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
}
