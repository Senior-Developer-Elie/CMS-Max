<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMockupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mockups', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('mockup_id', 20)->unique();
			$table->text('image_path');
			$table->string('url', 70)->unique();
			$table->string('title', 191)->nullable();
			$table->string('color', 191)->nullable();
			$table->string('align', 191);
			$table->string('ip_address', 191);
			$table->string('email', 191)->nullable();
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
		Schema::drop('mockups');
	}

}
