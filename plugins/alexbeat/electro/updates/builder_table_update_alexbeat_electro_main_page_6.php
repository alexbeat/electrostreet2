<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAlexbeatElectroMainPage6 extends Migration
{
    public function up()
    {
        Schema::table('alexbeat_electro_main_page', function($table)
        {
            $table->text('card_categories_items')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('alexbeat_electro_main_page', function($table)
        {
            $table->dropColumn('card_categories_items');
        });
    }
}
