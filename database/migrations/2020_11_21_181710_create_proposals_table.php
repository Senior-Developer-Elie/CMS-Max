<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProposalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('proposals', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->text('request', 65535);
			$table->string('status', 50);
			$table->string('full_name')->nullable();
			$table->string('job_title')->nullable();
			$table->text('signature', 65535)->nullable();
			$table->timestamps();
			$table->dateTime('signed_at')->nullable();
			$table->boolean('sold')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('proposals');
	}

}
