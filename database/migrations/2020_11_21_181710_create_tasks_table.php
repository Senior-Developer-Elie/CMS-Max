<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->text('name')->nullable();
			$table->text('description')->nullable();
			$table->bigInteger('stage_id')->default(1);
			$table->bigInteger('assignee_id')->nullable();
			$table->bigInteger('priority')->default(1);
			$table->timestamps();
			$table->string('dev_url', 191)->nullable()->default('');
			$table->string('live_url', 191)->nullable()->default('');
			$table->string('mail_host', 191)->nullable();
			$table->text('pre_live')->nullable();
			$table->date('due_date')->nullable();
			$table->integer('client_id')->nullable();
			$table->boolean('completed')->default(0);
			$table->date('completed_at')->nullable();
			$table->integer('website_id')->nullable();
			$table->string('sitemap', 512)->nullable();
			$table->string('home_page_copy', 512)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tasks');
	}

}
