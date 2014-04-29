<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_category', function($table)
		{
			$table->increments('id');
			$table->string('seo_category_name');
			$table->string('category_name');
			$table->string('category_description');
			$table->text('category_meta_title');
			$table->text('category_meta_description');
			$table->text('category_meta_keyword');
			$table->tinyInteger('category_level');
			$table->bigInteger('parent_category_id');
			$table->bigInteger('category_left');
			$table->bigInteger('category_right');
			$table->date('date_added');
			$table->bigInteger('display_order');
			$table->string('available_sort_options');
			$table->string('image_name');
			$table->string('image_ext');
			$table->string('image_width');
			$table->string('image_height');
			$table->enum('is_featured_category', array('Yes', 'No'))->default('No');
			$table->enum('use_parent_meta_detail', array('Yes', 'No'))->default('No');
			$table->enum('status', array('active', 'inactive'))->default('active');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_category');
	}

}
