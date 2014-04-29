<?php namespace Agriya\Webshoppack;

class ProductController extends \BaseController {

	public static function showList($cat_id = 0)
	{
		$productService = new ProductService();

    	$root_category_id = $productService->getRootCategoryId();
		$cat_list_arr = $productService->getCategoriesList($cat_id);
		//stores the product count in each category..
		$productService->getCountForProducts();
    	$cat_list = $productService->populateProductCategoryList($cat_id);
    	$q = $productService->buildProductQuery($cat_id);
    	$list_prod_serviceobj = $productService;
    	//$perPage = Config::get('mp_product.market_place_product_per_page_list');
		$product_details = $q->paginate('10');
		$product_total_count = $product_details->getTotal();
		$category_name = "";
		if($cat_id > 0)
		{
			$category_name = $productService->getCategoryName($cat_id);
		}
		$subcat = false;
		if($cat_id > 0)
		{
			$subcat = true;
		}

    	return \View::make(\Config::get('webshoppack::product_list'), compact('cat_list', 'product_details', 'cat_list_arr', 'subcat', 'product_total_count', 'category_name', 'list_prod_serviceobj'));
	}

	public function productList()
	{
		$productService = new ProductService();
		$is_search_done = 0;
		if(\Input::has('srchproduct_submit'))
		{
			$is_search_done = 1;
			$productService->setSearchFields(\Input::all());
		}
		$user = \Config::get('webshoppack::logged_user_id');
		$user_id = $user();
		$status_list = $productService->getProductStatusArr();
		$category_list =  $productService->getCategoryDropOptions();
		$q = $productService->buildMyProductQuery();
		$perPage	= \Config::get('webshoppack::paginate');
		$product_list = $q->paginate($perPage);
		return \View::make('webshoppack::productList', compact('product_list', 'productService', 'status_list', 'category_list', 'is_search_done'));
	}

	public function postProductAction()
	{
		$productService = new ProductService();
		$error_msg = \Lang::get('webshoppack::myProducts.product_invalid_action');
		$sucess_msg = '';
		if(\Input::has('product_action') && \Input::has('p_id'))
		{
			$p_id = \Input::get('p_id');
			$product_action = \Input::get('product_action');

			//Validate product id
			$user = \Config::get('webshoppack::logged_user_id');
			$logged_user_id = $user();
			$p_details = Product::whereRaw('id = ? AND product_status != ? AND product_user_id = ?', array($p_id, 'Deleted', $logged_user_id))->first();
			if(count($p_details) > 0)
			{
				switch($product_action)
				{
					# Delete product
					case 'delete':
						$error_msg = '';
						# Product status is changed as Deleted
						$status = $productService->deleteProduct($p_id, $p_details);
						# Display delete success msg
						if($status)
						{
							$sucess_msg = \Lang::get('webshoppack::myProducts.product_success_deleted');
						}
						else
						{
							$error_msg = \Lang::get('webshoppack::myProducts.product_error_on_action');
						}
						break;

					# Set featured
					case 'feature':
						# Product featured status is changed
						if($p_details['product_status'] == 'Ok' && $p_details['is_user_featured_product'] == 'No')
						{
							$error_msg = '';
							$status = $productService->changeFeaturedStatus($p_id, $p_details, 'Yes');
							# Display success msg
							if($status)
							{
								$sucess_msg = \Lang::get('webshoppack::myProducts.product_featured_success_msg');
							}
							else
							{
								$error_msg = \Lang::get('webshoppack::myProducts.product_error_on_action');
							}
						}
						break;

					# Remove featured
					case 'unfeature':
						# Product featured status is changed
						if($p_details['product_status'] == 'Ok' && $p_details['is_user_featured_product'] == 'Yes')
						{
							$error_msg = '';
							$status = $productService->changeFeaturedStatus($p_id, $p_details, 'No');
							# Display success msg
							if($status)
							{
								$sucess_msg = \Lang::get('webshoppack::myProducts.product_unfeatured_success_msg');
							}
							else
							{
								$error_msg = \Lang::get('webshoppack::myProducts.product_error_on_action');
							}
						}
						break;
				}
			}
		}
		if($sucess_msg != '')
		{
			return \Redirect::to(\Config::get('webshoppack::myProducts'))->with('success_message', $sucess_msg);
		}
		return \Redirect::to(\Config::get('webshoppack::myProducts'))->with('error_message', $error_msg);
	}
}