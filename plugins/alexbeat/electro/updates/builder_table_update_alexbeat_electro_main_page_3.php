<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAlexbeatElectroMainPage3 extends Migration
{
    public function up()
    {
        Schema::table('alexbeat_electro_main_page', function($table)
        {
            $table->string('top_banners_block1_link')->nullable();
            $table->string('top_banners_block2_link')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('alexbeat_electro_main_page', function($table)
        {
            $table->dropColumn('top_banners_block1_link');
            $table->dropColumn('top_banners_block2_link');
        });
    }
}
