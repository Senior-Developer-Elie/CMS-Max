<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteApiProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_api_products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('website_id')->unsigned();
            $table->string('key')->index();
            $table->float('value')->default(0.00);
            $table->integer('frequency')->default(1);

            $table->foreign('website_id')->references('id')->on('websites')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_apiproducts');
    }
}
