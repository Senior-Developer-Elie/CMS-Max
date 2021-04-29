<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialMediaCheckListIdColumnToWebsiteSocialMediaCheckListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('website_social_media_check_lists', function (Blueprint $table) {
            $table->bigInteger('social_media_check_list_id')->unsigned();

            $table->foreign('social_media_check_list_id', 'wsmcl_social_media_check_list_id_foreign')
                ->references('id')
                ->on('social_media_check_lists')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('website_social_media_check_lists', function (Blueprint $table) {
            $table->dropForeign('social_media_check_list_id_foreign');
            $table->dropColumn('social_media_check_list_id');
        });
    }
}
