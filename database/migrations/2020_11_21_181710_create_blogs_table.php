<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blogs', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('website_id');
			$table->text('name')->nullable();
			$table->dateTime('desired_date');
			$table->boolean('marked')->default(0);
			$table->string('blog_url', 191)->nullable();
			$table->timestamps();
			$table->bigInteger('completed_by')->nullable();
			$table->dateTime('completed_at')->nullable();
			$table->string('blog_website', 191)->default('');
			$table->text('blog_image')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('blogs');
	}

}
