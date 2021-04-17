<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialMediaColumnsToWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->bigInteger('social_media_stage_id')->unsigned()->nullable();
            $table->integer('social_media_stage_order');

            $table->foreign('social_media_stage_id')
                ->references('id')
                ->on('social_media_stages')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropForeign(['social_media_stage_id']);
            $table->dropColumn('social_media_stage_id');
            $table->dropColumn('social_media_stage_order');
        });
    }
}
