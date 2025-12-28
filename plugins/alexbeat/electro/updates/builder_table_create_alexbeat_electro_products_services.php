<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAlexbeatElectroProductsServices extends Migration
{
    public function up()
    {
        Schema::create('alexbeat_electro_products_services', function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('service_product_id')->unsigned();
            $table->integer('customer_group_id')->nullable()->unsigned()->default(0);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('alexbeat_electro_products_services');
    }
}
