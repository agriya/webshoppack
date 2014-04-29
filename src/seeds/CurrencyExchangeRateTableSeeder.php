<?php namespace Agriya\Webshoppack;

use Illuminate\Database\Seeder;

class CurrencyExchangeRateTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$destinationPath = \Config::get("webshoppack::currency_seeder_file");
		$file_path = \public_path().'/'.$destinationPath;
		$handle = fopen($file_path, 'r');
		if(!$handle) return;
		$currency_list = array();
		while (($data = fgetcsv($handle, 0, "\t", '"')) !== FALSE)
	    {
	        foreach($data as $key=>$value) {
	            $data[$key] = str_replace("'", "", $value);
	        }
	        $currency_list[] = implode(",", $data);
		}

		if(!empty($currency_list))
		{
			foreach($currency_list as $currency_key => $currency_val)
			{
				$currency_str =  explode(",", $currency_val);
				if(count($currency_str) > 0)
				{
					$basicdata['country'] = trim($currency_str[0]);
					$basicdata['country_code'] = trim($currency_str[1]);
					$basicdata['currency_code'] = trim($currency_str[2]);
					$basicdata['currency_symbol'] = trim($currency_str[3]);
					$basicdata['currency_name'] = trim($currency_str[4]);
					$basicdata['exchange_rate'] = trim($currency_str[5]);
					$basicdata['status'] = trim($currency_str[6]);
					$basicdata['paypal_supported'] = trim($currency_str[7]);
					$basicdata['display_currency'] = trim($currency_str[8]);
					\DB::table('currency_exchange_rate')->insert($basicdata);
				}
			}
			fclose($handle);
		}
	}
}