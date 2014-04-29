<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyExchangeRateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('currency_exchange_rate', function($table)
		{
			$table->increments('id');
			$table->string('country');
			$table->string('country_code', 10);
			$table->string('currency_code', 10);
			$table->string('currency_symbol', 25);
			$table->string('currency_name');
			$table->string('exchange_rate', 20);
			$table->enum('status', array('Active', 'InActive'))->default('Active');
			$table->enum('paypal_supported', array('Yes', 'No'))->default('No');
			$table->enum('display_currency', array('Yes', 'No'))->default('No');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('currency_exchange_rate');
	}

}
