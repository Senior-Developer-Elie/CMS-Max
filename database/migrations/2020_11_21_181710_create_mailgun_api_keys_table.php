<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMailgunApiKeysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mailgun_api_keys', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('domain', 191)->unique();
			$table->string('key', 191);
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
		Schema::drop('mailgun_api_keys');
	}

}
