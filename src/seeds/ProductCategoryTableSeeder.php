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
		$now = \DB::raw('NOW()');

		$basicdata['seo_category_name'] = 'Root';
		$basicdata['category_name'] = 'Root';
		$basicdata['category_level'] = 0;
		$basicdata['parent_category_id'] = 0;
		$basicdata['category_left'] = 1;
		$basicdata['category_right'] = 6;
		$basicdata['date_added'] = $now;
		$basicdata['display_order'] = 0;
		$basicdata['available_sort_options'] = 'all';
		$basicdata['image_width'] = 0;
		$basicdata['image_height'] = 0;
		\DB::table('product_category')->insert($basicdata);

		$basicdata_1['seo_category_name'] = 'templates';
		$basicdata_1['category_name'] = 'Templates';
		$basicdata_1['category_level'] = 1;
		$basicdata_1['parent_category_id'] = 1;
		$basicdata_1['category_left'] = 2;
		$basicdata_1['category_right'] = 3;
		$basicdata_1['date_added'] = $now;
		$basicdata_1['display_order'] = 0;
		$basicdata_1['available_sort_options'] = 'all';
		$basicdata_1['image_width'] = 0;
		$basicdata_1['image_height'] = 0;
		\DB::table('product_category')->insert($basicdata_1);

		$basicdata_2['seo_category_name'] = 'e-books';
		$basicdata_2['category_name'] = 'E-Books';
		$basicdata_2['category_level'] = 1;
		$basicdata_2['parent_category_id'] = 1;
		$basicdata_2['category_left'] = 4;
		$basicdata_2['category_right'] = 5;
		$basicdata_2['date_added'] = $now;
		$basicdata_2['display_order'] = 0;
		$basicdata_2['available_sort_options'] = 'all';
		$basicdata_2['image_width'] = 0;
		$basicdata_2['image_height'] = 0;
		\DB::table('product_category')->insert($basicdata_2);
	}
}