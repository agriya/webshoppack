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
    	//$list_prod_serviceobj = $productService;
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

    	return \View::make(\Config::get('webshoppack::product_list'), compact('cat_list', 'product_details', 'cat_list_arr', 'subcat', 'product_total_count', 'category_name'));
	}

	public static function getAdd()
	{
		$productService = new ProductService();
		/*if(!$productService->checkIsShopNameExist() || !$productService->checkIsShopPaypalUpdated())
    	{
			return Redirect::to('users/shop-details');
		}*/

		$d_arr = $category_main_arr = $category_sub_arr = array();

		$user = \Config::get('webshoppack::logged_user_id');
		$logged_user_id = $user();

		$title = trans('webshoppack::product.add_product_title');
		$error_msg = '';
		$category_id = (\Input::get('my_category_id') == '')? \Input::old('my_category_id', 1): \Input::get('my_category_id', 1);

		//To add/edit product details
		$p_id = (\Input::get('id') == '')? \Input::old('id'): \Input::get('id');
		$tab = (\Input::get('p') == '')? \Input::old('p', 'basic'): \Input::get('p', 'basic');
		$p_url = \URL::action('Agriya\Webshoppack\ProductController@postAdd');
		$action = 'add';
		$p_details = new Product();
		if($p_id != '')
		{
			//To validate product id
			if(is_numeric($p_id))
			{
				$p_details = Product::whereRaw('id = ? AND product_user_id = ?', array($p_id, $logged_user_id))->first();
				if(count($p_details) > 0)
				{
					$p_details = $p_details->toArray();
					$p_url = \URL::action('Agriya\Webshoppack\ProductController@postEdit');
					$category_id = $p_details['product_category_id'];
					$action = 'edit';
				}
				else
				{
					$p_id = '';
					$error_msg = trans('webshoppack::product.invalid_product_id');
				}
			}
			else
			{
				$p_id = '';
				$error_msg = trans('webshoppack::product.invalid_product_id');
			}
		}

		//Render user alert mesage
		if($error_msg != '')
		{
			Session::put('error_message', $error_msg);
		}

		//$this->header->setMetaTitle($title);
		if($tab == 'basic')
		{
			$section_arr = $productService->getProductSectionDropList($logged_user_id);

			//To get category list
			$category_main_arr = $productService->getCategoryListArr();
			$cat_list = $productService->getAllTopLevelCategoryIds($category_id);
			$top_cat_list_arr = explode(',', $cat_list);
			$top_cat_count = count($top_cat_list_arr);
			if($top_cat_count > 1)
			{

				foreach($top_cat_list_arr AS $sel_key => $top_cat_id)
				{
					$category_sub_arr[$top_cat_id] = $productService->getSubCategoryList($top_cat_id);
				}
			}
			$d_arr['my_selected_categories'] = $cat_list;
			$d_arr['top_cat_list_arr'] = $top_cat_list_arr;
			$d_arr['my_category_id'] = $category_id;
			$d_arr['top_cat_count'] = $top_cat_count;
			$d_arr['root_category_id'] = $productService->root_category_id;
		}
		elseif($tab == 'price')
		{
			$d_arr['currency_list'] = $productService->getCurrencyList();
		}
		elseif($tab == 'attribute')
		{
			$d_arr['attr_arr'] = $productService->getAttributesList($category_id, $p_id);
		}
		elseif($tab == 'preview_files')
		{
			$d_arr['p_img_arr'] = $productService->populateProductDefaultThumbImages($p_id);
			$d_arr['thumb_no_image'] = CUtil::DISP_IMAGE(145, 145, Config::get("mp_product.photos_thumb_width"), Config::get("mp_product.photos_thumb_height"), true);
			$d_arr['default_no_image'] = CUtil::DISP_IMAGE(578, 385, Config::get("mp_product.photos_large_width"), Config::get("mp_product.photos_large_height"), true);
			$d_arr['resources_arr'] = $productService->populateProductResources($p_id, 'Image');
		}
		elseif($tab == 'download_files')
		{
			$d_arr['resources_arr'] = $productService->populateProductResources($p_id, 'Archive', 'Yes');
		}
		elseif($tab == 'publish')
		{
			$d_arr['product_notes'] = $productService->getProductNotes($p_id);
		}
		$d_arr['p'] = $tab;
		$d_arr['tab_list'] = $productService->getTabList($p_id, $p_details, $action);
		if((!isset($d_arr['tab_list'][$tab])) || (isset($d_arr['tab_list'][$tab]) && !$d_arr['tab_list'][$tab]))
		{
			//return \Redirect::to('products/add')->with('error_message', trans('common.invalid_access'));
			/*while (key($d_arr['tab_list']) !== $tab) next($d_arr['tab_list']); //To set the current array pointer to current tab..
			if(!prev($d_arr['tab_list']))
			{
				return Redirect::to('products/add')->with('error_message', trans('common.invalid_access'));
			}*/
		}
		$service_obj = $productService;
		return \View::make(\Config::get('webshoppack::product_add'), compact('d_arr', 'section_arr', 'p_details', 'p_id', 'p_url', 'category_main_arr', 'category_sub_arr', 'service_obj', 'action', 'category_id'));
	}

	public function postAdd()
	{
		$productService = new ProductService();
		if(\Input::has('add_product'))
		{
			$input_arr = \Input::All();
			$input_arr['product_preview_type'] = 'image'; //Make default preview type is image now, We will get preview type from user in future..
			$validator_arr = $productService->getproductValidation($input_arr);
			$validator = \Validator::make($input_arr, $validator_arr['rules'], $validator_arr['messages']);
			if($validator->passes())
			{
				$add_product = $productService->addProduct($input_arr);
				if($add_product > 0)
				{
					//To redirect to next tab..
					return \Redirect::to(\Config::get('webshoppack::uri').'/add?id='.$add_product.'&p=price');
				}
			}
			else
			{
				return \Redirect::to(\Config::get('webshoppack::uri').'/add')->with('error_message', trans('common.correct_errors'))->withInput()->withErrors($validator);
			}
		}
		return \Redirect::to(\Config::get('webshoppack::uri').'/add');
	}

	public function postEdit()
	{

	}

	public function getProductSubCategories()
	{
		$productService = new ProductService();
		if(\Request::ajax())
		{
			$input_arr = \Input::all();
			$category_id = $input_arr['category_id'];
			if(is_numeric($category_id) && $category_id > 0)
			{
				$sub_categories_arr = $productService->getSubCategoryList($category_id);
				$disp_result = '';
				if(count($sub_categories_arr) > 1)
				{
					$disp_result .= '<select id="sub_category_'.$category_id.'" name="sub_category_'.$category_id.'" class="control-label fn_subCat_'.$category_id.'" onchange="listSubCategories(\'sub_category_'.$category_id.'\', \''.$category_id.'\');">';
					foreach($sub_categories_arr AS $category_id => $category_name)
					{
						$disp_result .= '<option value="'.$category_id.'">'.$category_name.'</option>';
					}
					$disp_result .= '</select>';
				}
				else
				{
					$disp_result .= '<div class="fn_clsNoSubCategryFound alert alert-info" id="sub_category_'.$category_id.'" name="sub_category_'.$category_id.'">'.trans('webshoppack::product.product_no_subcategories').'</div>';
				}
				echo $disp_result;
				echo '~~~';
				$cat_list = $productService->getAllTopLevelCategoryIds($category_id);
				echo $cat_list.'~~~';
			}
		}
	}

	public function postAddSectionName()
	{
		$productService = new ProductService();
		if(\Request::ajax())
		{
			$input_arr = \Input::all();
			$v_arr = $productService->getSectionNameValidation();
			$validator = \Validator::make($input_arr, $v_arr['rules'], $v_arr['messages']);
			if($validator->passes())
			{
				$section_id = $productService->addSectionName($input_arr);
				if($section_id == '')
				{
					echo json_encode(array('status'=>'error', 'error_message' => trans("webshoppack::product.invalid_section_name_add_request")));
					return ;
				}
				echo json_encode(array('status'=>'success', 'error_message'=>'', 'section_id' => $section_id, 'section_name' => $input_arr['section_name']));
				return ;
			}
			else
			{
				$errors = $validator->getMessageBag()->toArray();
				echo json_encode(array('status'=>'error', 'error_message' => $errors['section_name']));
				return ;
			}

		}
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