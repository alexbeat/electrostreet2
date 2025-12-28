<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAlexbeatElectroCategoryProductServices extends Migration
{
    public function up()
    {
        Schema::table('alexbeat_electro_category_product_services', function($table)
        {
            $table->integer('customer_group_id')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('alexbeat_electro_category_product_services', function($table)
        {
            $table->integer('customer_group_id')->nullable(false)->change();
        });
    }
}
