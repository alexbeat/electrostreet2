<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAlexbeatElectroLegalsPage extends Migration
{
    public function up()
    {
        Schema::table('alexbeat_electro_legals_page', function($table)
        {
            $table->string('numbers_bg')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('alexbeat_electro_legals_page', function($table)
        {
            $table->dropColumn('numbers_bg');
        });
    }
}
