<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAlexbeatElectroLegalsPage extends Migration
{
    public function up()
    {
        Schema::create('alexbeat_electro_legals_page', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('h1')->nullable();
            $table->text('description')->nullable();
            $table->text('advantages_items')->nullable();
            $table->string('numbers_title')->nullable();
            $table->text('numbers_items')->nullable();
            $table->string('how_title')->nullable();
            $table->string('how_image')->nullable();
            $table->text('how_items')->nullable();
            $table->string('how_form_button_title')->nullable();
            $table->text('service_items')->nullable();
            $table->string('callback_bg')->nullable();
            $table->string('callback_form_title')->nullable();
            $table->string('callback_form_subtitle')->nullable();
            $table->string('callback_form_button_title')->nullable();
            $table->string('callback_form_agreement')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('alexbeat_electro_legals_page');
    }
}
