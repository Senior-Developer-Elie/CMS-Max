<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMailgunSuppressionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mailgun_suppressions', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('type', 15)->index('type');
			$table->string('domain', 191)->index('domain');
			$table->string('address', 191)->nullable()->index('address');
			$table->text('error', 65535)->nullable();
			$table->dateTime('timestamp')->nullable();
			$table->timestamps();
			$table->boolean('archived')->default(0);
			$table->dateTime('archived_at')->nullable();
			$table->unique(['type','address','domain'], 'mailgun_suppressions_type_address_unique');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mailgun_suppressions');
	}

}
