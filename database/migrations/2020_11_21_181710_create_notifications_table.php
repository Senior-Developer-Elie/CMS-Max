<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('type', 191);
			$table->bigInteger('user_id');
			$table->bigInteger('triggered_by')->nullable();
			$table->bigInteger('reference_id')->nullable();
			$table->boolean('archived')->default(0);
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
		Schema::drop('notifications');
	}

}
