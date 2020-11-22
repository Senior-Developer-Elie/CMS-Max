<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInnerBlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inner_blogs', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('website_id');
			$table->bigInteger('priority')->default(0);
			$table->text('website')->nullable();
			$table->text('needed_text')->nullable();
			$table->boolean('marked')->default(0);
			$table->string('blog_url', 191)->nullable();
			$table->text('blog_image')->nullable();
			$table->bigInteger('completed_by')->nullable();
			$table->dateTime('completed_at')->nullable();
			$table->timestamps();
			$table->bigInteger('assignee_id')->nullable();
			$table->text('files')->nullable();
			$table->boolean('to_do')->default(1);
			$table->text('title')->nullable();
			$table->date('due_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('inner_blogs');
	}

}
