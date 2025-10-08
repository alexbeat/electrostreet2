<?php namespace Alexbeat\Electro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAlexbeatElectroMainPage2 extends Migration
{
    public function up()
    {
        Schema::table('alexbeat_electro_main_page', function($table)
        {
            $table->text('top_banners_items')->nullable();
            $table->string('top_banners_block1_subtitle')->nullable();
            $table->string('top_banners_block1_icon_class')->nullable();
            $table->string('top_banners_block2_title')->nullable();
            $table->string('top_banners_block2_subtitle')->nullable();
            $table->string('top_banners_block2_icon_class')->nullable();
            $table->string('ideal_transport_title')->nullable();
            $table->string('ideal_transport_button_title')->nullable();
            $table->text('ideal_transport_categories')->nullable();
            $table->text('brands_items')->nullable();
            $table->string('details_title')->nullable();
            $table->text('details_items')->nullable();
            $table->string('servicecenter_title')->nullable();
            $table->string('servicecenter_subtitle')->nullable();
            $table->string('servicecenter_image')->nullable();
            $table->string('servicecenter_bg')->nullable();
            $table->text('servicecenter_items')->nullable();
            $table->string('showroom_title')->nullable();
            $table->string('showroom_subtitle')->nullable();
            $table->string('franchise_title')->nullable();
            $table->string('franchise_subtitle')->nullable();
            $table->string('franchise_link_title')->nullable();
            $table->string('franchise_link')->nullable();
            $table->string('franchise_image')->nullable();
            $table->renameColumn('h1', 'top_banners_block1_title');
        });
    }
    
    public function down()
    {
        Schema::table('alexbeat_electro_main_page', function($table)
        {
            $table->dropColumn('top_banners_items');
            $table->dropColumn('top_banners_block1_subtitle');
            $table->dropColumn('top_banners_block1_icon_class');
            $table->dropColumn('top_banners_block2_title');
            $table->dropColumn('top_banners_block2_subtitle');
            $table->dropColumn('top_banners_block2_icon_class');
            $table->dropColumn('ideal_transport_title');
            $table->dropColumn('ideal_transport_button_title');
            $table->dropColumn('ideal_transport_categories');
            $table->dropColumn('brands_items');
            $table->dropColumn('details_title');
            $table->dropColumn('details_items');
            $table->dropColumn('servicecenter_title');
            $table->dropColumn('servicecenter_subtitle');
            $table->dropColumn('servicecenter_image');
            $table->dropColumn('servicecenter_bg');
            $table->dropColumn('servicecenter_items');
            $table->dropColumn('showroom_title');
            $table->dropColumn('showroom_subtitle');
            $table->dropColumn('franchise_title');
            $table->dropColumn('franchise_subtitle');
            $table->dropColumn('franchise_link_title');
            $table->dropColumn('franchise_link');
            $table->dropColumn('franchise_image');
            $table->renameColumn('top_banners_block1_title', 'h1');
        });
    }
}
