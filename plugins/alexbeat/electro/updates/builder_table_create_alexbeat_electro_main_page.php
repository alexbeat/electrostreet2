<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAlexbeatElectroMainPage extends Migration
{
    public function up()
    {
        Schema::create('alexbeat_electro_main_page', function($table)
        {
            $table->increments('id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('alexbeat_electro_main_page');
    }
}
