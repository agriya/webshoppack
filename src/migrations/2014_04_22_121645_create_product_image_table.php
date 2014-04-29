<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductImageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_image', function($table)
		{
			$table->increments('id');
			$table->bigInteger('product_id');
			$table->string('thumbnail_title');
			$table->string('thumbnail_img', 150);
			$table->string('thumbnail_ext', 4);
			$table->integer('thumbnail_width');
			$table->integer('thumbnail_height');
			$table->string('default_title');
			$table->string('default_img', 150);
			$table->string('default_ext', 4);
			$table->integer('default_width');
			$table->integer('default_height');
			$table->integer('default_orig_img_width');
			$table->integer('default_orig_img_height');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_image');
	}

}
