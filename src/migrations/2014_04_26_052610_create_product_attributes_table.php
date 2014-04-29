<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_attributes', function($table)
		{
			$table->increments('id');
			$table->string('attribute_label');
			$table->string('attribute_help_tip');
			$table->enum('attribute_question_type', array('text', 'textarea', 'select', 'check', 'option', 'multiselectlist'))->default('text');
			$table->text('default_value');
			$table->string('validation_rules');
			$table->date('date_added');
			$table->enum('is_searchable', array('yes', 'no'))->default('no');
			$table->enum('show_in_list', array('yes', 'no'))->default('yes');
			$table->string('description');
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
		Schema::drop('product_attributes');
	}

}
