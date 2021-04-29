<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\SocialMediaCheckList;

class CreateSocialMediaCheckListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_media_check_lists', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('target')->default(SocialMediaCheckList::CHECKLIST_TYPE_CORE);
            $table->string('text', 512);
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_media_check_lists');
    }
}
