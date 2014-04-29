<?php namespace Agriya\Webshoppack;

use View,Input,Validator,Response,URL;

class ShopController extends \BaseController
{
    public function getIndex()
    {

		$this->shopService = new ShopService();
    	$details = $this->shopService->getPaymentAnalyticsDetails();
    	$shop_details = $this->shopService->getShopDetails();
		$shop_paypal_details = $this->shopService->getShopPaypalDetails();
		//echo "<pre>";print_r($shop_paypal_details);echo "</pre>";
    	$country_arr = $this->shopService->getCountryList();
    	$shop_status = $details['shop_status'];
    	$breadcrumb_arr = array(trans("webshoppack::shopDetails.shop_details") => '');

    	return View::make('webshoppack::shopDetails', compact('details', 'shop_details', 'shop_status', 'breadcrumb_arr', 'country_arr', 'shop_paypal_details'));
	}

	public function postIndex()
	{
		$success_message = "";
		if(Input::has('submit_form'))
		{
			$this->shopService = new ShopService();

			switch(Input::get('submit_form'))
			{
				case 'update_policy':
					$details = $this->shopService->getPaymentAnalyticsDetails();
					$shop_details = $this->shopService->getShopDetails();
					$shop_status = 1;//Input::get('shop_status');
					$input_arr = Input::All();
					$validator_arr = $this->shopService->processValidation($input_arr);
					$validator = Validator::make($input_arr, $validator_arr['rules'], $validator_arr['messages']);
					if ($validator->fails())
					{
						$errors = $validator->errors();
						return View::make('webshoppack::shopPolicy', compact('errors', 'shop_details', 'shop_status'));
					}
					else
					{
						$shop_added_already = $this->shopService->updateShopDetails($input_arr);
						if($shop_added_already)
						{
							$success_message = trans("webshoppack::shopDetails.shop_details_updated_success");
						}
						else
						{
							$success_message = trans("webshoppack::shopDetails.shopdetails_added_productadd_success");
							$product_add_url = URL::to('products/add');
							$success_message = str_replace('VAR_ADDPRODUCT_LINK', $product_add_url, $success_message);
						}
						return View::make('webshoppack::shopPolicy', compact('success_message', 'shop_details', 'shop_status'));
					}
				break;
				case 'update_shop_paypal':
					$input_arr = Input::All();
					//echo "<pre>";print_r($input_arr);echo "</pre>";exit;
					$this->shopService->updateShopPaypal($input_arr);
					$shop_paypal_details = $this->shopService->getShopPaypalDetails();
					$success_message = trans("webshoppack::shopDetails.shop_paypal_updated_success");
					return View::make('webshoppack::shopPaypal', compact('success_message', 'shop_paypal_details'));
				break;
				case 'update_address':
					$input_arr = Input::All();
					$shop_details = $this->shopService->getShopDetails();
    				$country_arr = $this->shopService->getCountryList();
					$this->shopService->updateShopAddress($input_arr);
					$success_message = trans("webshoppack::shopDetails.shop_address_updated_success");
					return View::make('webshoppack::shopAddress', compact('success_message', 'shop_details', 'country_arr'));
				break;
				case 'update_banner':
					$input_arr = Input::All();
					if (Input::hasFile('shop_banner_image'))
					{
						if($_FILES['shop_banner_image']['error'])
						{
							$shop_details = $this->shopService->getShopDetails();
							$error_message = trans("webshoppack::common.uploader_max_file_size_err_msg");
							return View::make('webshoppack::shopBanner', compact('error_message', 'shop_details'));
						}
					}
					$rules = array('shop_banner_image' => 'Required|mimes:'.\Config::get("webshoppack::shop_uploader_allowed_extensions"),
									//'shop_banner_image' => 'mimes:'.Config::get("shop.shop_uploader_allowed_extensions").'|size:'.Config::get("shop.shop_image_uploader_allowed_file_size")
								);
					$message = array('shop_banner_image.mimes' => trans('webshoppack::common.uploader_allow_format_err_msg'),
								'shop_banner_image.size' => trans('webshoppack::common.uploader_max_file_size_err_msg'),
							);
					$v = Validator::make(Input::all(), $rules, $message);
					if ($v->fails())
					{
						$shop_details = $this->shopService->getShopDetails();
						$errors = $v->errors();
						return View::make('webshoppack::shopBanner', compact('errors', 'shop_details'));
					}
					else
					{
						$this->shopService->updateShopBanner($input_arr);
						$shop_details = $this->shopService->getShopDetails();
						$success_message = trans("webshoppack::shopDetails.shop_banner_updated_success");
						return View::make('webshoppack::shopBanner', compact('success_message', 'shop_details'));
					}
				break;
			}
		}
	}

	public function getDeleteShopImage()
	{
		$this->shopService = new ShopService();

		$resource_id 	= Input::get("resource_id");
		$imagename 		= Input::get("imagename");
		$imageext 		= Input::get("imageext");
		$imagefolder 	= Input::get("imagefolder");

		if($imagename != "")
		{
			$delete_status = $this->shopService->deleteShopImage($resource_id, $imagename, $imageext, \Config::get($imagefolder));
			if($delete_status)
			{
				return Response::json(array('result' => 'success'));
			}
		}
		return Response::json(array('result' => 'error'));
	}
}
?>