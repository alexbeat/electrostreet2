<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAlexbeatElectroMainPage extends Migration
{
    public function up()
    {
        Schema::table('alexbeat_electro_main_page', function($table)
        {
            $table->string('h1')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('alexbeat_electro_main_page', function($table)
        {
            $table->dropColumn('h1');
        });
    }
}
