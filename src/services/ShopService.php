<?php namespace Agriya\Webshoppack;
use Input,Image;
class ShopService
{
	public function getPaymentAnalyticsDetails()
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$logged_user_id = $user();
		$details = array();
		$details = UsersShopDetails::Select('paypal_id', 'shop_status')->where('user_id', $logged_user_id)->first();
		return $details;
	}

	public function getShopDetails($user_id = 0)
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$logged_user_id = $user();
		if($user_id == 0)
		{
			$user_id = $logged_user_id;
		}
		$shop_details = array();
		$shop_details = ShopDetails::Select('id', 'user_id', 'shop_name', 'url_slug', 'shop_slogan', 'shop_desc', 'shop_address1', 'shop_address2', 'shop_city', 'shop_state',
						'shop_zipcode', 'shop_country', 'shop_message', 'shop_contactinfo', 'image_name', 'image_ext', 'image_server_url', 't_height', 't_width')
						->where('user_id', $user_id)->first();
		return $shop_details;
	}

	public function getShopPaypalDetails($user_id = 0)
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$logged_user_id = $user();
		if($user_id == 0)
		{
			$user_id = $logged_user_id;
		}
		$user_id = $logged_user_id;

		$users = UsersShopDetails::Select('paypal_id')->where('user_id', $user_id)->first();
		return $users;
	}

	public function getCountryList()
	{
		$country_list_arr = array();
		$country_arr = CurrencyExchangeRate::whereRaw("status = ? ORDER BY country", array('Active'))->get(array('country', 'country_code'));
		foreach($country_arr AS $value)
			{
				$country_list_arr[$value['country_code']] = $value['country'];
			}
		return $country_list_arr;
	}

	/*
	public function updateShopAnalytics($input)
	{
		$data_arr['shop_analytics_code'] = $input['shop_analytics_code'];
		User::where('user_id', $this->logged_user_id)->update($data_arr);
	}*/

	public function processValidation($input_arr)
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$this->logged_user_id = $user();
		$rules = array(
				'shop_name' => 'Required|Min:'.\Config::get('webshoppack::shopname_min_length').'|Max:'.\Config::get('webshoppack::shopname_max_length').'|unique:shop_details,shop_name,'.$this->logged_user_id.',user_id',
				'url_slug' => 'Required|unique:shop_details,url_slug,'.$this->logged_user_id.',user_id',
				'shop_slogan' => 'Min:'.\Config::get('webshoppack::shopslogan_min_length').'|Max:'.\Config::get('webshoppack::shopslogan_max_length'),
				'shop_desc' => 'Min:'.\Config::get('webshoppack::fieldlength_shop_description_min').'|Max:'.\Config::get('webshoppack::fieldlength_shop_description_max'),
				'shop_contactinfo' => 'Min:'.\Config::get('webshoppack::fieldlength_shop_contactinfo_min').'|Max:'.\Config::get('webshoppack::fieldlength_shop_contactinfo_max'),
		);
		$message = array('shop_name.unique' => trans('webshoppack::shopDetails.shopname_already_exists'),
						'url_slug.unique' => trans('webshoppack::shopDetails.shopurlslug_already_exists'),
						);
		return array('rules' => $rules, 'messages' => $message);
	}

	public function updateShopOwnerStatus($input)
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$this->logged_user_id = $user();
		//$data_arr['shop_status'] = $input['shop_status'];
		$data_arr['is_shop_owner'] = 'Yes';
		UsersShopDetails::whereRaw('user_id = ?', array($this->logged_user_id))->update($data_arr);
	}

	public function updateShopPaypal($input)
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$logged_user_id = $user();
		//echo "<pre>";print_r($input);echo "</pre>";
		//$data_arr['shop_status'] = $input['shop_status'];
		$data_arr['paypal_id'] = $input['paypal_id'];
		UsersShopDetails::whereRaw('user_id = ?', array($logged_user_id))->update($data_arr);
	}

	public function isShopAlreadyAdded()
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$this->logged_user_id = $user();
		$shop_count = ShopDetails::whereRaw('user_id = ?', array($this->logged_user_id))->count();
		if($shop_count > 0)
		{
			return true;
		}
		return false;
	}

	public function updateShopDetails($input)
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$this->logged_user_id = $user();

		$this->updateShopOwnerStatus($input);
		$already_added = true;
		if($this->isShopAlreadyAdded())
		{
			//Update shop details
			$data_arr['shop_name'] = $input['shop_name'];
			$data_arr['url_slug'] = $input['url_slug'];
			$data_arr['shop_slogan'] = $input['shop_slogan'];
			$data_arr['shop_desc'] = $input['shop_desc'];
		//	$data_arr['shop_message'] = $input['shop_message'];
			$data_arr['shop_contactinfo'] = $input['shop_contactinfo'];
			ShopDetails::whereRaw('user_id = ?', array($this->logged_user_id))->update($data_arr);
		}
		else
		{
			$already_added = false;
			$data_arr['shop_name'] = $input['shop_name'];
			$data_arr['url_slug'] = $input['url_slug'];
			$data_arr['shop_slogan'] = $input['shop_slogan'];
			$data_arr['shop_desc'] = $input['shop_desc'];
		//	$data_arr['shop_message'] = $input['shop_message'];
			$data_arr['shop_contactinfo'] = $input['shop_contactinfo'];
			$data_arr['user_id'] = $this->logged_user_id;
			$shop = new ShopDetails;
			$shop->addNew($data_arr);
		}
		return $already_added;
	}

	public function updateShopAddress($input)
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$this->logged_user_id = $user();

		if($this->isShopAlreadyAdded())
		{
			$data_arr['shop_country'] = $input['shop_country'];
			$data_arr['shop_address1'] = $input['shop_address1'];
			$data_arr['shop_address2'] = $input['shop_address2'];
			$data_arr['shop_city'] = $input['shop_city'];
			$data_arr['shop_state'] = $input['shop_state'];
			$data_arr['shop_zipcode'] = $input['shop_zipcode'];
			ShopDetails::whereRaw('user_id = ?', array($this->logged_user_id))->update($data_arr);
		}
		else
		{
			$data_arr['shop_country'] = $input['shop_country'];
			$data_arr['shop_address1'] = $input['shop_address1'];
			$data_arr['shop_address2'] = $input['shop_address2'];
			$data_arr['shop_city'] = $input['shop_city'];
			$data_arr['shop_state'] = $input['shop_state'];
			$data_arr['shop_zipcode'] = $input['shop_zipcode'];
			$data_arr['user_id'] = $this->logged_user_id;
			$shop = new ShopDetails;
			$shop->addNew($data_arr);
		}
	}

	public function updateShopBanner($input)
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$this->logged_user_id = $user();

		$img_arr = array();
		if (Input::hasFile('shop_banner_image'))
		{
			$file = Input::file('shop_banner_image');
			$image_ext = $file->getClientOriginalExtension();
			$image_name = \Str::random(20);
			$destinationpath = \URL::asset(\Config::get("webshoppack::shop_image_folder"));
			$img_arr = $this->updateBannerImage($file, $image_ext, $image_name, $destinationpath);
			if($this->isShopAlreadyAdded())
			{
				ShopDetails::whereRaw('user_id = ?', array($this->logged_user_id))->update($img_arr);
			}
			else
			{
				$shop = new ShopDetails;
				$shop->addNew($img_arr);
			}
		}
	}

	public function updateBannerImage($file, $image_ext, $image_name, $destinationpath)
	{
		$return_arr = array();
		$config_path = \Config::get('webshoppack::shop_image_folder');
		CUtil::chkAndCreateFolder($config_path);

		// open file a image resource
		Image::make($file->getRealPath())->save(\Config::get("webshoppack::shop_image_folder").$image_name.'_O.'.$image_ext);

		list($width,$height)= getimagesize($file);
		list($upload_img['width'], $upload_img['height']) = getimagesize(base_path().'/public/'.$config_path.$image_name.'_O.'.$image_ext);

		$thumb_width = \Config::get("webshoppack::shop_image_thumb_width");
		$thumb_height = \Config::get("webshoppack::shop_image_thumb_height");
		if(isset($thumb_width) && isset($thumb_height))
		{
			$timg_size = CUtil::DISP_IMAGE($thumb_width, $thumb_height, $upload_img['width'], $upload_img['height'], true);
			Image::make($file->getRealPath())
				->resize($thumb_width, $thumb_height, true, false)
				->save($config_path.$image_name.'_T.'.$image_ext);
		}

		$img_path = base_path().'/public/'.$config_path;
		list($upload_input['thumb_width'], $upload_input['thumb_height']) = getimagesize($img_path.$image_name.'_T.'.$image_ext);

		if($this->isShopAlreadyAdded())
		{
			$this->deleteExistingImageFiles();
		}

		$return_arr = array('image_ext' => $image_ext, 'image_name' => $image_name, 'image_server_url' => $destinationpath,
									't_width' => $upload_input['thumb_width'], 't_height' => $upload_input['thumb_height']);
		return $return_arr;
	}

	public function deleteExistingImageFiles()
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$this->logged_user_id = $user();

		$existing_images = ShopDetails::whereRaw('user_id = ?', array($this->logged_user_id))->first();
		if(count($existing_images) > 0 && $existing_images['image_name'] != '')
		{
			$data_arr = array('image_name' => '', 'image_ext' => '', 'image_server_url' => '', 't_height' => '', 't_width' => '');
			$affectedRows = ShopDetails::whereRaw('id = ? AND user_id = ?', array($existing_images['id'], $this->logged_user_id))->update($data_arr);
			$this->deleteImageFiles($existing_images['image_name'], $existing_images['image_ext'], \Config::get("webshoppack::shop_image_folder"));
		}
	}

	public function deleteShopImage($id, $filename, $ext, $folder_name)
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$this->logged_user_id = $user();

		$data_arr = array('image_name' => '', 'image_ext' => '', 'image_server_url' => '', 't_height' => '', 't_width' => '');
		$affectedRows = ShopDetails::whereRaw('id = ? AND user_id = ?', array($id, $this->logged_user_id))->update($data_arr);
		if($affectedRows)
		{
			$this->deleteImageFiles($filename, $ext, $folder_name);
			return true;
		}
		return false;
	}

	public function deleteImageFiles($filename, $ext, $folder_name)
	{
		if (file_exists($folder_name.$filename."_T.".$ext))
		{
			unlink($folder_name.$filename."_T.".$ext);
		}
		if (file_exists($folder_name.$filename."_O.".$ext))
		{
			unlink($folder_name.$filename."_O.".$ext);
		}
	}
/*
	public static function getShopImage($shop_id, $image_size = "thumb", $shop_image_info = array(), $cache = true)
	{
		$image_exists = false;
		$image_details = array();

		if(count($shop_image_info) == 0)
		{
			$shop_image_info = MpShopDetails::whereRaw('id = ? ', array($shop_id))->first();
		}
		if(count($shop_image_info) > 0 && $shop_image_info['image_name'] != '')
		{
			$image_exists = true;
			$image_details["image_id"] = $shop_image_info['id'];
			$image_details["image_ext"] = $shop_image_info['image_ext'];
			$image_details["image_name"] = $shop_image_info['image_name'];
			$image_details["image_server_url"] = $shop_image_info['image_server_url'];
			$image_details["image_thumb_width"] = $shop_image_info['t_width'];
			$image_details["image_thumb_height"] = $shop_image_info['t_height'];
			$image_details["image_folder"] = Config::get("shop.shop_image_folder");
		}

		$image_path = "";
		$image_url = "";
		$image_attr = "";
		if($image_exists)
		{
			$image_path = URL::asset(Config::get("shop.shop_image_folder"))."/";
		}
		$cfg_shop_img_thumb_width = Config::get("shop.shop_image_thumb_width");
		$cfg_shop_img_thumb_height = Config::get("shop.shop_image_thumb_height");

		switch($image_size)
		{
			case "thumb":

				$image_url = "";

				$image_attr = "";

				if($image_exists)
				{
					$image_url =  $image_path . $image_details["image_name"]."_T.".$image_details["image_ext"];
					$image_attr = CUtil::TPL_DISP_IMAGE($cfg_shop_img_thumb_width, $cfg_shop_img_thumb_height, $image_details["image_thumb_width"], $image_details["image_thumb_height"]);
				}
				break;

			default:

				$image_url = "";
				$image_attr = "";

				if($image_exists)
				{
					$image_url =  $image_path . $image_details["image_name"]."_T.".$image_details["image_ext"];
					$image_attr = CUtil::TPL_DISP_IMAGE(90, 90, $image_details["image_thumb_width"], $image_details["image_thumb_height"]);
				}
		}
		$image_details['image_url'] = $image_url;
		$image_details['image_attr'] = $image_attr;
		return $image_details;
	}*/
}