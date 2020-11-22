<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfitLossHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profit_loss_histories', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->text('data');
			$table->dateTime('desired_date');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('profit_loss_histories');
	}

}
