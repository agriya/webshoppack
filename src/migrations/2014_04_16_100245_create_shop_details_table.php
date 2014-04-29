<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_details', function($table)
		{
			$table->increments('id');
			$table->bigInteger('user_id');
			$table->string('shop_name', 150);
			$table->string('url_slug', 150);
			$table->string('shop_slogan', 200);
			$table->text('shop_desc');
			$table->string('shop_address1', 150);
			$table->string('shop_address2', 150);
			$table->string('shop_city', 50);
			$table->string('shop_state', 50);
			$table->string('shop_zipcode', 15);
			$table->string('shop_country', 10);
			$table->text('shop_message');
			$table->text('shop_contactinfo');
			$table->string('image_name', 100);
			$table->string('image_ext', 10);
			$table->string('image_server_url');
			$table->string('t_height', 15);
			$table->string('t_width', 15);
			$table->enum('is_featured_shop', array('Yes', 'No'))->default('No');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_details');
	}

}
