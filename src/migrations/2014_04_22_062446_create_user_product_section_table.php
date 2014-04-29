<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProductSectionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_product_section', function($table)
		{
			$table->increments('id');
			$table->bigInteger('user_id');
			$table->string('section_name', 150);
			$table->dateTime('date_added');
			$table->enum('status', array('Yes', 'No'))->default('Yes');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_product_section');
	}

}
