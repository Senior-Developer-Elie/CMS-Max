<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteSocialMediaCheckListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_social_media_check_lists', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('website_id')->unsigned();
            $table->string('key');
            $table->dateTime('completed_at');
            $table->bigInteger('user_id')->unsigned()->nullable();

            $table->foreign('website_id')
                ->references('id')
                ->on('websites')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('website_social_media_check_lists');
    }
}
