<?php namespace Alexbeat\Electro\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class LegalsPage extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class
    ];

    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Alexbeat.Electro', 'electro-pages', 'electro-legals-page');
    }

    public function index()
    {
        $model = $this->formCreateModelObject()->first();

        if (!$model) {
            $model = $this->formCreateModelObject();
            $model->forceSave();
        }

        return Backend::redirect("alexbeat/electro/legalspage/update/{$model->id}");
    }
}
