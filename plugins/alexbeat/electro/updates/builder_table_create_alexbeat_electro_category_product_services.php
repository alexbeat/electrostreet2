<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAlexbeatElectroCategoryProductServices extends Migration
{
    public function up()
    {
        Schema::create('alexbeat_electro_category_product_services', function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('service_product_id')->unsigned();
            $table->integer('customer_group_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('alexbeat_electro_category_product_services');
    }
}
