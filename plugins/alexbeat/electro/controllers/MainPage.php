<?php

namespace Alexbeat\Electro\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class MainPage extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\RelationController::class,
    ];

    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Alexbeat.Electro', 'electro-pages', 'electro-main-page');
    }
}
