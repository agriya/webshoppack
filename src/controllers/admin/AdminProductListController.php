<?php namespace Agriya\Webshoppack;

class AdminProductListController extends \BaseController {

	public function getIndex()
	{
		$this->service = new AdminProductListService();

		$d_arr = $products_arr = array();
		$error_msg = '';
		$per_page	= \Config::get('webshoppack::shop_product_per_page_list');

		$d_arr['allow_to_change_status'] = true;
		$d_arr['product_list_title'] =  trans('webshoppack::admin/productList.product_list_title');
		$d_arr['category_arr'] =  $this->service->getCategoryDropOptions();
		$d_arr['feature_arr'] =  $this->service->getFeatureStatusDropOptions();
		$d_arr['status_arr'] =  $this->service->getProductStatusDropOptions();
		$this->service->setProductsSearchArr(\Input::all());
		$products_arr = $this->service->buildProductsQuery()->paginate($per_page);

		$service_obj = $this->service;

		return \View::make('webshoppack::admin.productList', compact('d_arr', 'products_arr', 'service_obj'));
	}

	public function postProductAction()
	{
		$this->service = new AdminProductListService();

		$error_msg = trans('webshoppack::admin/productList.product_invalid_action');
		$sucess_msg = '';
		if(\Input::has('product_action') && \Input::has('p_id'))
		{
			$p_id = \Input::get('p_id');
			$product_action = \Input::get('product_action');

			//Validate product id
			$p_details = Product::whereRaw('id = ? AND product_status != ?', array($p_id, 'Deleted'))->first();
			if(count($p_details) > 0)
			{
				switch($product_action)
				{
					# Activate product
					case 'activate':
						# Product status is changed as Ok
						if($p_details['product_status'] == 'ToActivate')
						{
							$error_msg = '';
							$status = $this->service->activateProduct($p_id, $p_details);
							# Display activate success msg
							if($status)
							{
								$sucess_msg = trans('webshoppack::admin/productList.product_success_activated');
							}
							else
							{
								$error_msg = trans('webshoppack::admin/productList.product_error_on_action');
							}
						}
						break;

					# Activate product
					case 'disapprove':
						# Product status is changed as Ok
						if($p_details['product_status'] == 'ToActivate')
						{
							$error_msg = '';
							$status = $this->service->disapproveProduct($p_id, $p_details);
							# Display activate success msg
							if($status)
							{
								$sucess_msg = trans('webshoppack::admin/productList.product_success_disapproved');
							}
							else
							{
								$error_msg = trans('webshoppack::admin/productList.product_error_on_action');
							}
						}
						break;

					# Delete product
					case 'delete':
						$error_msg = '';
						# Product status is changed as Deleted
						$status = $this->service->deleteProduct($p_id, $p_details);
						# Display delete success msg
						if($status)
						{
							$sucess_msg = trans('webshoppack::admin/productList.product_success_deleted');
						}
						else
						{
							$error_msg = trans('webshoppack::admin/productList.product_error_on_action');
						}
						break;

					# Set featured
					case 'feature':
						# Product featured status is changed
						if($p_details['product_status'] == 'Ok' && $p_details['is_featured_product'] == 'No')
						{
							$error_msg = '';
							$status = $this->service->changeFeaturedStatus($p_id, $p_details, 'Yes');
							# Display success msg
							if($status)
							{
								$sucess_msg = trans('webshoppack::admin/productList.product_featured_success_msg');
							}
							else
							{
								$error_msg = trans('webshoppack::admin/productList.product_error_on_action');
							}
						}
						break;

					# Remove featured
					case 'unfeature':
						# Product featured status is changed
						if($p_details['product_status'] == 'Ok' && $p_details['is_featured_product'] == 'Yes')
						{
							$error_msg = '';
							$status = $this->service->changeFeaturedStatus($p_id, $p_details, 'No');
							# Display success msg
							if($status)
							{
								$sucess_msg = trans('webshoppack::admin/productList.product_unfeatured_success_msg');
							}
							else
							{
								$error_msg = trans('webshoppack::admin/productList.product_error_on_action');
							}
						}
						break;
				}
			}
		}
		if($sucess_msg != '')
		{
			return \Redirect::to(\Config::get('webshoppack::admin_uri').'/list')->with('success_message', $sucess_msg);
		}
		return \Redirect::to(\Config::get('webshoppack::admin_uri').'/list')->with('error_message', $error_msg);
	}

	public function getChangeStatus()
	{
		$this->service = new AdminProductListService();
		$p_id = (\Input::get('p_id') == '')? \Input::old('p_id'): \Input::get('p_id');
		$p_details = Product::whereRaw('id = ? AND product_status != ?', array($p_id, 'Deleted'))->first();
		$error_msg = '';
		$allow_to_view_form = false;
		$d_arr = array();
		if(count($p_details) > 0)
		{
			$error_msg = trans('webshoppack::admin/productList.product_invalid_action');
			if($p_details['product_status'] == 'ToActivate')
			{
				$allow_to_view_form = true;
				$error_msg = '';
				$d_arr['status_drop'] = $this->service->getStatusDropList('ToActivate');
			}
		}
		if($error_msg != '')
		{
			Session::put('error_message', $error_msg);
		}
		return \View::make('webshoppack::admin/manageProductStatus', compact('d_arr', 'allow_to_view_form', 'p_id'));
	}

	public function postChangeStatus()
	{
		$this->service = new AdminProductListService();
		$p_id = \Input::get('p_id');
		$p_details = Product::whereRaw('id = ? AND product_status != ?', array($p_id, 'Deleted'))->first();
		$error_msg = trans('webshoppack::admin/productList.product_invalid_action');
		$sucess_msg = '';
		if(count($p_details) > 0)
		{
			if(\Input::has('change_status'))
			{
				$product_status = \Input::get('product_status');
				$input_arr = \Input::all();
				switch($product_status)
				{
					# Activate product
					case 'activate':
						# Product status is changed as Ok
						if($p_details['product_status'] == 'ToActivate')
						{
							$error_msg = '';
							$status = $this->service->activateProduct($p_id, $p_details, $input_arr);
							# Display activate success msg
							if($status)
							{
								$sucess_msg = trans('webshoppack::admin/productList.product_success_activated');
							}
							else
							{
								$error_msg = trans('webshoppack::admin/productList.product_error_on_action');
							}
						}
						break;

					# Activate product
					case 'disapprove':
						# Product status is changed as Ok
						if($p_details['product_status'] == 'ToActivate')
						{
							$error_msg = '';
							$status = $this->service->disapproveProduct($p_id, $p_details, $input_arr);
							# Display activate success msg
							if($status)
							{
								$sucess_msg = trans('webshoppack::admin/productList.product_success_disapproved');
							}
							else
							{
								$error_msg = trans('webshoppack::admin/productList.product_error_on_action');
							}
						}
						break;
				}
			}
		}
		if($sucess_msg != '')
		{
			return \Redirect::to(\Config::get('webshoppack::admin_uri').'/list/change-status')->with('success_message', $sucess_msg);
		}
		return \Redirect::to(\Config::get('webshoppack::admin_uri').'/list/change-status')->with('error_message', $error_msg);
	}
}