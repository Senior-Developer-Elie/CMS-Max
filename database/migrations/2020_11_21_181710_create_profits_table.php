<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profits', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('key', 191)->default('');
			$table->string('name', 191);
			$table->text('description')->nullable();
			$table->float('price');
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
		Schema::drop('profits');
	}

}
