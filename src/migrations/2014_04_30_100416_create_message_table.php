<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('message', function($table)
		{
			$table->increments('id');
			$table->dateTime('date_added');
			$table->bigInteger('from_user_id');
			$table->bigInteger('to_user_id');
			$table->bigInteger('last_replied_by');
			$table->dateTime('last_replied_date');
			$table->string('subject', 200);
			$table->integer('reply_count');
			$table->text('message_text');
			$table->tinyInteger('is_deleted')->default(0);
			$table->tinyInteger('replied')->default(0);
			$table->string('rel_type', 100);
			$table->integer('rel_id');
			$table->string('rel_table', 100);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('message');
	}

}
