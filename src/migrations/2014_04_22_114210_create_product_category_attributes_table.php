<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoryAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_category_attributes', function($table)
		{
			$table->increments('id');
			$table->bigInteger('attribute_id');
			$table->bigInteger('category_id');
			$table->date('date_added');
			$table->bigInteger('display_order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_category_attributes');
	}

}
