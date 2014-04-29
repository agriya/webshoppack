<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product', function($table)
		{
			$table->increments('id');
			$table->string('product_code', 20);
			$table->string('product_name');
			$table->text('product_description');
			$table->text('product_support_content');
			$table->string('meta_title');
			$table->string('meta_keyword');
			$table->string('meta_description');
			$table->string('product_highlight_text', 500);
			$table->string('product_slogan');
			$table->decimal('product_price', 15, 2);
			$table->decimal('product_price_usd', 20, 2);
			$table->string('product_price_currency', 10);
			$table->bigInteger('product_user_id');
			$table->bigInteger('product_sold');
			$table->date('product_added_date');
			$table->string('url_slug', 150);
			$table->string('demo_url');
			$table->text('demo_details');
			$table->bigInteger('product_category_id');
			$table->string('product_tags');
			$table->bigInteger('total_views');
			$table->enum('is_featured_product', array('Yes', 'No'))->default('No');
			$table->enum('is_user_featured_product', array('Yes', 'No'))->default('No');
			$table->dateTime('date_activated');
			$table->decimal('product_discount_price', 15, 2);
			$table->decimal('product_discount_price_usd', 20, 2);
			$table->date('product_discount_fromdate');
			$table->date('product_discount_todate');
			$table->enum('product_preview_type', array('image', 'audio', 'video'))->default('image');
			$table->enum('is_free_product', array('Yes', 'No'))->default('No');
			$table->dateTime('last_updated_date');
			$table->bigInteger('total_downloads');
			$table->string('product_moreinfo_url');
			$table->enum('global_transaction_fee_used', array('Yes', 'No'))->default('No');
			$table->enum('site_transaction_fee_type', array('Flat', 'Percentage', 'Mix'))->default('Flat');
			$table->double('site_transaction_fee', 10, 2);
			$table->double('site_transaction_fee_percent', 10, 2);
			$table->enum('is_downloadable_product', array('Yes', 'No'))->default('Yes');
			$table->bigInteger('user_section_id');
			$table->bigInteger('delivery_days');
			$table->dateTime('date_expires');
			$table->integer('default_orig_img_width');
			$table->integer('default_orig_img_height');
			$table->enum('product_status', array('Draft', 'Ok', 'Deleted', 'ToActivate', 'NotApproved'))->default('ToActivate');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product');
	}
}