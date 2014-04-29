<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductResourceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_resource', function($table)
		{
			$table->increments('id');
			$table->bigInteger('product_id');
			$table->enum('resource_type', array('Archive', 'Audio', 'Video', 'Image', 'Other'))->default('Other');
			$table->enum('is_downloadable', array('Yes', 'No'))->default('No');
			$table->string('filename', 150);
			$table->string('ext', 10);
			$table->string('title', 150);
			$table->enum('default_flag', array('Yes', 'No'))->default('No');
			$table->string('server_url');
			$table->integer('display_order');
			$table->integer('width');
			$table->integer('height');
			$table->integer('l_width');
			$table->integer('l_height');
			$table->integer('t_width');
			$table->integer('t_height');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_resource');
	}

}
