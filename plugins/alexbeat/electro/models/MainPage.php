<?php

namespace Alexbeat\Electro\Models;

class MainPage extends \System\Models\SettingModel
{
    public $settingsCode = 'alexbeat_electro_main_page';

    public $settingsFields = 'fields.yaml';

    public function getCategoryOptions() {
        $categories = Category::with('category_description')->orderBy('category_id')->orderBy('parent_id')->get();
    
        // Формируем массив опций из коллекции
        $categoryOptions = [];
        foreach ($categories as $category) {
            if ($category->category_description) {
                $categoryOptions[$category->category_id] = $category->category_description->name.' ('.$category->category_id.')';
            }
        }
    
        return $categoryOptions;
    }
    

}