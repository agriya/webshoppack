<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttributeOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_attribute_options', function($table)
		{
			$table->increments('id');
			$table->bigInteger('attribute_id');
			$table->string('option_label', 150);
			$table->string('option_value', 150);
			$table->enum('is_default_option', array('yes', 'no'))->default('no');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_attribute_options');
	}

}
