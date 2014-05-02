## Webshop Package
A Laravel 4 package for basic webshop

## Installation

Add the following to you composer.json file

    "agriya/webshoppack": "dev-master"

Run

    composer update

Add the following to app/config/app.php

    'Agriya\Webshoppack\WebshoppackServiceProvider',

Publish the config

    php artisan config:publish agriya/webshoppack

Publish the asset

	php artisan asset:publish agriya/webshoppack

Run the migration

    php artisan migrate --package="agriya/webshoppack"

Run the db seed

	php artisan db:seed --class="Agriya\Webshoppack\ProductCategoryTableSeeder"

	php artisan db:seed --class="Agriya\Webshoppack\CurrencyExchangeRateTableSeeder"

Add the following to app/routes.php

	Route::controller(Config::get('webshoppack::uri').'/view/{slug_url}', 'Agriya\Webshoppack\ViewProductController');

	Route::group(array('before'	=>	'validate_admin'), function(){
		Route::controller(
			Config::get('webshoppack::admin_uri').'/list', 'Agriya\Webshoppack\AdminProductListController'
		);
		Route::controller(
			Config::get('webshoppack::admin_uri').'/view/{slug_url}', 'Agriya\Webshoppack\AdminViewProductController'
		);
		Route::controller(
			Config::get('webshoppack::admin_shop_uri'), 'Agriya\Webshoppack\AdminManageShopController'
		);
		Route::controller(
			Config::get('webshoppack::admin_uri').'/add', 'Agriya\Webshoppack\AdminProductAddController'
		);
		Route::controller(Config::get('webshoppack::admin_product_catalog_uri'), 'Agriya\Webshoppack\AdminManageProductCatalogController');

		Route::controller(Config::get('webshoppack::admin_product_cat_uri'), 'Agriya\Webshoppack\AdminProductCategoryController');

		Route::controller(Config::get('webshoppack::admin_product_cat_attr_uri'), 'Agriya\Webshoppack\AdminCategoryAttributesController');

		Route::controller(Config::get('webshoppack::admin_product_attr_uri'), 'Agriya\Webshoppack\AdminProductAttributesController');
	});

	Route::group(array('before' => 'validate_login'), function()
	{
		Route::controller(
			Config::get('webshoppack::uri').'/add', 'Agriya\Webshoppack\ProductAddController'
		);
		Route::get(Config::get('webshoppack::shop_uri').'/user/message/add/{user_code}', 'Agriya\Webshoppack\MessageAddController@getAdd');
		Route::post(Config::get('webshoppack::shop_uri').'/user/message/add/{user_code}', 'Agriya\Webshoppack\MessageAddController@postAdd');
		Route::controller(
			Config::get('webshoppack::shop_uri').'/users/shop-details', 'Agriya\Webshoppack\ShopController'
		);
		Route::controller(
			Config::get('webshoppack::shop_uri').'/{url_slug}', 'Agriya\Webshoppack\ViewShopController'
		);
		Route::controller(
			Config::get('webshoppack::shop_uri'), 'Agriya\Webshoppack\ListShopController'
		);
		Route::get(Config::get('webshoppack::myProducts'), 'Agriya\Webshoppack\ProductController@productList');
		Route::post(Config::get('webshoppack::myProducts').'/deleteproduct', 'Agriya\Webshoppack\ProductController@postProductAction');
	});
	Route::get(
		Config::get('webshoppack::uri').'/{path}', function($path) {
	    $path = substr($path, 0, 1) == '/' ? substr($path, 1) : $path;
	    $slugs = explode('/', $path);

	    $check = function($page, $slugs) use(&$check) {
		if($page->parent_category_id == 1) {
		    return true;
		}

		$parent = Agriya\Webshoppack\ProductCategory::find($page->parent_category_id);
		if($parent->seo_category_name == array_pop($slugs)) {
		    return $check($parent, $slugs);
		}
	    };
		$error = true;
	    foreach(Agriya\Webshoppack\ProductCategory::where('seo_category_name', '=', array_pop($slugs))->get() as $page) {
		if($check($page, $slugs)) {
			$error = true;
		    break;
		}
	    }
	    //todo : handle error
	    if(!isset($page)) {
			return Agriya\Webshoppack\ProductController::showList(0);
		}
		else {
			return Agriya\Webshoppack\ProductController::showList($page->id);
		}
	})->where('path', '.+');

	Route::get(
		Config::get('webshoppack::uri'), 'Agriya\Webshoppack\ProductController@showList'
	);

## Add the following links in member & admin layouts

	Shops list - URL::to(Config::get('webshoppack::shop_uri'))
	Products list - URL::to(Config::get('webshoppack::uri'))

	if (Sentry::check()) use below links
		Shop Settings - URL::to(Config::get('webshoppack::shop_uri'))
		Manage Products - URL::to(Config::get('webshoppack::myProducts'))
		Add Product - URL::to(Config::get('webshoppack::uri').'/add')

	if (Sentry::check() && hasAdminAccess) use below links
		Manage products - URL::to(Config::get('webshoppack::admin_uri').'/list')
		Manage category - URL::to(Config::get('webshoppack::admin_product_catalog_uri'))
		Manage Attributes - URL::to(Config::get('webshoppack::admin_product_attr_uri'))
		Manage shops - URL::to(Config::get('webshoppack::admin_shop_uri'))
