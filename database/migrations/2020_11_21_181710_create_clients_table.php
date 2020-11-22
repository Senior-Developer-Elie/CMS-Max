<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clients', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->text('name');
			$table->text('contacts')->nullable();
			$table->text('notes')->nullable();
			$table->timestamps();
			$table->bigInteger('api_id')->nullable();
			$table->dateTime('synced_at')->nullable();
			$table->dateTime('api_updated_at')->nullable();
			$table->boolean('archived')->default(0);
			$table->dateTime('archived_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('clients');
	}

}
