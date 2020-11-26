<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWebsitesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('websites', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('name', 100)->nullable();
			$table->text('website')->nullable();
			$table->string('frequency', 50)->nullable();
			$table->string('target_area', 191)->nullable();
			$table->timestamps();
			$table->dateTime('start_date')->nullable();
			$table->bigInteger('blog_industry_id')->nullable();
			$table->bigInteger('assignee_id')->nullable()->index('assignee_id');
			$table->text('notes')->nullable();
			$table->boolean('is_blog_client')->default(1);
			$table->bigInteger('client_id')->index('client_id');
			$table->bigInteger('api_id')->nullable();
			$table->dateTime('synced_at')->nullable();
			$table->string('type', 191)->default('regular');
			$table->string('affiliate', 191)->nullable();
			$table->string('dns', 191)->nullable();
			$table->text('payment_gateway')->nullable();
			$table->string('email', 191)->nullable();
			$table->boolean('sync_from_client')->default(1);
			$table->string('sitemap', 191)->nullable();
			$table->string('left_review', 191)->nullable();
			$table->string('on_portfolio', 191)->nullable();
			$table->string('shipping_method', 191)->nullable();
			$table->bigInteger('stage_id')->default(1);
			$table->bigInteger('priority')->default(0);
			$table->text('post_live')->nullable();
			$table->text('marketing_notes')->nullable();
			$table->date('completed_at')->nullable();
			$table->boolean('archived')->default(0);
			$table->dateTime('archived_at')->nullable();
			$table->boolean('payroll_archived')->default(0);
			$table->dateTime('payroll_archived_at')->nullable();
			$table->string('mailgun_sender', 191)->nullable();
			$table->text('mid', 65535)->nullable();
			$table->text('control_scan_user', 65535)->nullable();
			$table->text('control_scan_pass', 65535)->nullable();
			$table->date('control_scan_renewal_date')->nullable();
			$table->text('data_studio_link', 65535)->nullable();
			$table->boolean('social_media_archived')->default(0);
			$table->text('social_media_notes', 65535)->nullable();
			$table->boolean('credit_card_archived')->default(0);
			$table->text('credit_card_notes', 65535)->nullable();
			$table->string('drive', 512)->default('');
			$table->boolean('post_live_check_archived')->default(0);
			$table->boolean('chargebee')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('websites');
	}

}
