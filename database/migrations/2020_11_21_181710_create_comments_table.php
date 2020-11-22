<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('task_id');
			$table->bigInteger('author_id');
			$table->text('content')->nullable();
			$table->timestamps();
			$table->boolean('pin')->default(0);
			$table->string('file_path', 191)->nullable();
			$table->string('file_origin_name', 191)->nullable();
			$table->string('type', 191)->default('text');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
	}

}
