<?php

return array(

/*
*Application URI
*/
'uri'	=>	'webshop/product',

/*
*My product URI
*/
'myProducts'	=>	'webshop/myproducts',

/*
*This view for Product list
*/
'product_list'	=>	'webshoppack::index',

/*
*For myproduct add  URL
*/
'product_add_url'	=>	'product/add',

/*
*For myproduct view  URL
*/
'product_list_view'	=>	'product/view',

/*
*For product view  URL
*/
'view_product' => 'webshoppack::viewProduct',

/*
*For product view right  URL
*/
'view_productright' => 'webshoppack::viewProductRightBlock',

/*
*For product view right  URL
*/
'display_productprice' => 'webshoppack::displayProductPrice',

/*
*For myproduct edit  URL
*/
'product_list_edit'	=>	'product/add?id=',

/*
*This view for Product list
*/
'manage_product_title'	=>	'Manage products',

/*
*	Pagination value for product list
*/
'paginate'	=>	'2',
/*
*This view for Product add
*/
'product_add'	=>	'webshoppack::addProduct',

/*
*This view for Product add
*/
'admin_product_add'	=>	'webshoppack::admin.addProduct',

/**
 * Page title of the jobs index page
 *
 * @type string
 */
'index_page_title' => 'Products',

/**
 * Meta description of the jobs index page
 *
 * @type string
 */
'index_page_meta_description' => 'This is the description for the products page',

/**
 * Meta keywords of the jobs index page
 *
 * @type string
 */
'index_page_meta_keywords' => 'These are the keywords for the products page',

/*
* Setting - package layout
*/
'package_layout' => 'webshoppack::base',

/*
* Setting - package layout for admin
*/
'package_admin_layout' => 'webshoppack::admin',

/*
*This view for email file
*/
'mail_view' => 'webshoppack::mail',

/*
*This view for email file
*/
'admin_mail' => 'r.senthilvasan@agriya.in',

/*
*Shop URI
*/
'shop_uri'	=>	'webshop/shop',

/**
 * The permission option is the highest-level authentication check that lets you define a closure that should return true if the current user
 * is allowed to view the admin section. Any "falsey" response will send the user back to the 'login_path' defined below.
 *
 * @type closure
 */
'permission'=> function()
{
	return Sentry::check();
	//return true;
},

/**
 * The permission option is the highest-level authentication check that lets you define a closure that should return true if the current user
 * is allowed to view the admin section. Any "falsey" response will send the user back to the 'login_path' defined below.
 *
 * @type closure
 */
'admin_permission'=> function()
{
	return Sentry::getUser()->hasAnyAccess(['system', 'system.Admin']);
	//return true;
},

/**
 * The login path is the path where the user if they fail a permission check
 *
 * @type string
 */
'login_path' => 'webshop/users/login',

/**
 * The login path is the path where the user if they fail a permission check
 *
 * @type string
 */
'logout_path' => 'webshop/users/logout',

/**
 * This is the key of the return path that is sent with the redirection to your login_action. Session::get('redirect') will hold the return URL.
 *
 * @type string
 */
'login_redirect_key' => 'redirect',

/**
 * Logged user id
 *
 * @type string
 */
'logged_user_id'=> function()
{
	$user_id = 0;
	if(Sentry::check()) {
		$user_id = Sentry::getUser()->id;
	}
	return $user_id;
},

/**
 * is user Logged
 *
 * @type boolean
 */
'is_logged_in'=> function()
{
	if(Sentry::check()) {
		return true;
	}
	return false;
},

/**
 * is Admin user
 *
 * @type boolean
 */
'is_admin'=> function()
{
	if(Sentry::getUser()->hasAnyAccess(['system', 'system.Admin'])) {
		return true;
	}
	return false;
},

/**
 * The login path is the path where the user if they fail a permission check
 *
 * @type string
 */
'user_fields' => array('fname' => 'first_name', 'lname' => 'last_name', 'email' => 'email'),


'user_id_field' => 'id',

'user_table' => 'users',

'title_min_length' => '5',
'title_max_length' => '100',
'summary_max_length' => '500',
'product_tab_validation' => false,
'download_files_is_mandatory' => 0,
'can_upload_free_product' => 1,
'photos_thumb_width' => 125,
'photos_thumb_height' => 95,
'photos_large_width' => 561,
'photos_large_height' => 561,
'photos_folder' => 'packages/agriya/webshoppack/files/product_image/',
'photos_large_no_image' => 'product-default-561.jpg',
'photos_thumb_no_image' => 'product-thumb-170.jpg',
'photos_indexsmall_no_image' => 'product-94.jpg',
'thumb_format_arr' => array('jpg','jpeg','gif','png'),
'default_format_arr' => array('jpg','jpeg','gif','png'),
'default_max_size' => 5, // in MB
'preview_format_arr' => array('jpg','jpeg','gif','png'),
'preview_max_size' => 5, // in MB
'preview_max' => 5, // maximum number of files
'thumb_max_size' => 5, // in MB
'photos_indexsmall_width' => 94,
'photos_indexsmall_height' => 94,
'photos_indexsmall_name' => 'IS',
'package_name'	=>	'Webshop pack',
'admin_uri'	=>	'webshop/admin/product',
'admin_shop_uri'	=>	'webshop/admin/shop',
'logout_uri'	=>	'webshop/admin/product',
'download_files_is_mandatory' => 1,
'download_format_arr' => array('zip', 'rar'),
'download_max_size' => 500, // in MB
'archive_folder' => 'packages/agriya/webshoppack/files/product_zip/',
'product_auto_approve' => 0,
'site_default_currency' => 'USD',
'site_exchange_rate' => 5,
'shopname_min_length' => 3,
'shopname_max_length' => 50,
'shopslogan_min_length' => 3,
'shopslogan_max_length' => 75,
'fieldlength_shop_description_min' => 200,
'fieldlength_shop_description_max' => 2000,
'fieldlength_shop_contactinfo_min' => 50,
'fieldlength_shop_contactinfo_max' => 500,
'shop_image_folder' => 'files/shop_image/',
'shop_uploader_allowed_extensions' => 'jpg,jpeg,png,gif',
'shop_image_uploader_allowed_file_size' => '3072',
'shop_image_thumb_width' => '697',
'shop_image_thumb_height' => '194',
'shop_image_allowed_upload_limit' => '1',
'site_cookie_prefix' => 'webshop',
'locatorhq_api_username' => 'Muralidharan5841',
'locatorhq_api_key' => 'ad498e79838d37537da4d3ac39253169ecfa0685',
'currency_is_multi_currency_support' => false,
'user_image_folder' => 'packages/agriya/webshoppack/files/user_image/',
'user_image_small_width' => 32,
'user_image_small_height' => 32,
'user_image_thumb_width' => 62,
'user_image_thumb_height' => 62,
'user_image_large_width' => 140,
'user_image_large_height' => 140,
'currency_seeder_file' => 'packages/agriya/webshoppack/files/currency.txt',
'shop_product_per_page_list' => '10',
'admin_product_catalog_uri'	=>	'webshop/admin/manage-product-catalog',
'admin_product_cat_uri'	=>	'webshop/admin/product-category',
'admin_product_cat_attr_uri'	=>	'webshop/admin/category-attributes',
'admin_product_attr_uri'	=>	'webshop/admin/product-attributes',
'product_category_image_folder' => 'packages/agriya/webshoppack/files/product_category_image/',
'product_category_uploader_allowed_extensions' => 'jpg,jpeg,png,gif',
'product_category_image_uploader_allowed_file_size' => '5',
'product_category_image_thumb_width' => '176',
'product_category_image_thumb_height' => '160',
'product_category_image_allowed_upload_limit' => '1',
'admin_email' => 'm.bindu@agriya.in',
'ui_options' => array('select'=>'Dropdown','check'=>'Checkbox','option'=>'Radio button','multiselectlist'=>'Multi-select list'),
'ui_no_options' => array('text'=>'Textbox','textarea'=>'Textarea'),
'validation_rules' => array(
		array(
			'name'=>'required',
			'caption'=>'Required',
			'input_box'=> false,
			'validation'=>false
		),
		array(
			'name'=>'numeric',
			'caption'=>'Numeric',
			'input_box'=> false,
			'validation'=>false
		),
		array(
			'name'=>'alpha',
			'caption'=>'Alpha',
			'input_box'=> false,
			'validation'=>false
		),
		array(
			'name'=>'maxlength',
			'caption'=>'Max Length',
			'input_box'=> true,
			'validation'=>'required|number'
		),
		array(
			'name'=>'minlength',
			'caption'=>'Min Length',
			'input_box'=> true,
			'validation'=>'required|number'
		)
	),
'attribute_per_page_list' => 10,
'list_paging_sort_by' => array(
	'id'		=> 'id',
	'views' 	=> 'total_views',
	'download'	=> 'product_sold',
	'free'		=> 'is_free_product',
	'featured'	=> 'featured'
),
'product_per_page_list' => 20,
'shop_per_page_list' => 10,
'product_search_include_title'	=> 1,
//'tags_csv_file' => 'packages/agriya/webshoppack/files/tags.csv',
);
?>