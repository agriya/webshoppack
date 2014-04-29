<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_log', function($table)
		{
			$table->increments('id');
			$table->bigInteger('product_id');
			$table->dateTime('date_added');
			$table->enum('added_by', array('User', 'Admin', 'Staff'))->default('User');
			$table->bigInteger('user_id');
			$table->text('notes');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_log');
	}

}
