<?php namespace Agriya\Webshoppack;

use Illuminate\Database\Seeder;

class ProductCategoryTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$now = date('Y-m-d H:i:s');

		$basicdata['seo_category_name'] = 'Root';
		$basicdata['category_name'] = 'Root';
		$basicdata['category_level'] = 0;
		$basicdata['parent_category_id'] = 0;
		$basicdata['category_left'] = 1;
		$basicdata['category_right'] = 2;
		$basicdata['date_added'] = $now;
		$basicdata['display_order'] = 0;
		$basicdata['available_sort_options'] = 'all';
		$basicdata['image_width'] = 0;
		$basicdata['image_height'] = 0;
		\DB::table('product_category')->insert($basicdata);
	}
}