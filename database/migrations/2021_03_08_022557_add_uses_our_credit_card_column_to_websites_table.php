<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsesOurCreditCardColumnToWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->boolean('uses_our_credit_card')->default(0);
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
            $table->dropColumn('uses_our_credit_card');
        });
    }
}
