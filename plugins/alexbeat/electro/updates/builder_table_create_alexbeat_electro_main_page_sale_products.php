<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAlexbeatElectroMainPageSaleProducts extends Migration
{
    public function up()
    {
        Schema::create('alexbeat_electro_main_page_sale_products', function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('product_id');
            $table->integer('sort_order')->unsigned()->default(0);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('alexbeat_electro_main_page_sale_products');
    }
}
