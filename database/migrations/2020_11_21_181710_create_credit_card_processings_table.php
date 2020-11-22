<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCreditCardProcessingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('credit_card_processings', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->timestamps();
			$table->string('company_name', 191)->nullable();
			$table->text('payment_gateway')->nullable();
			$table->text('mid', 65535)->nullable();
			$table->text('control_scan_user', 65535)->nullable();
			$table->text('control_scan_pass', 65535)->nullable();
			$table->date('control_scan_renewal_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('credit_card_processings');
	}

}
