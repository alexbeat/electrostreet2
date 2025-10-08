<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAlexbeatElectroMainPage5 extends Migration
{
    public function up()
    {
        Schema::table('alexbeat_electro_main_page', function($table)
        {
            $table->renameColumn('servicecenter_image_mobile', 'servicecenter_bg_mobile');
        });
    }
    
    public function down()
    {
        Schema::table('alexbeat_electro_main_page', function($table)
        {
            $table->renameColumn('servicecenter_bg_mobile', 'servicecenter_image_mobile');
        });
    }
}
