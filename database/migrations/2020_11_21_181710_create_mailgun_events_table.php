<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMailgunEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mailgun_events', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('event_id', 191)->nullable();
			$table->string('event', 191)->nullable();
			$table->string('severity', 191)->nullable();
			$table->string('storage_url', 512)->nullable();
			$table->string('storage_key', 191)->nullable();
			$table->string('recipient_domain', 191)->nullable();
			$table->string('reason', 191)->nullable();
			$table->string('log_level', 191)->nullable();
			$table->string('envelope_sender', 191)->nullable();
			$table->string('envelope_transport', 191)->nullable();
			$table->string('envelope_targets', 191)->nullable();
			$table->string('recipient', 191)->nullable();
			$table->string('message_to', 191)->nullable();
			$table->string('message_id', 191)->nullable();
			$table->string('message_from', 191)->nullable();
			$table->string('message_subject', 512)->nullable();
			$table->integer('delivery_status_code')->nullable();
			$table->text('delivery_status_message', 65535)->nullable();
			$table->text('delivery_status_description', 65535)->nullable();
			$table->dateTime('timestamp')->nullable();
			$table->string('signature', 191)->nullable();
			$table->string('signature_token', 191)->nullable();
			$table->dateTime('signature_timestamp')->nullable();
			$table->boolean('archived')->default(0);
			$table->timestamps();
			$table->string('domain', 191)->nullable();
			$table->boolean('supression')->default(0);
			$table->text('supression_error', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mailgun_events');
	}

}
