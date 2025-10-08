<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAlexbeatElectroMainPage4 extends Migration
{
    public function up()
    {
        Schema::table('alexbeat_electro_main_page', function($table)
        {
            $table->string('servicecenter_image_mobile')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('alexbeat_electro_main_page', function($table)
        {
            $table->dropColumn('servicecenter_image_mobile');
        });
    }
}
