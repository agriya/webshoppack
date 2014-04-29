<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersShopDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_shop_details', function($table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->enum('is_shop_owner', array('Yes', 'No'))->default('No');
			$table->tinyInteger('shop_status')->default(1);
			$table->bigInteger('total_products');
			$table->string('paypal_id', 100);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_shop_details');
	}

}
