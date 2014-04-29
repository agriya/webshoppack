<?php namespace Agriya\Webshoppack;
class AdminManageShopService
{
	public function setShopSrchArr($input)
	{
		$this->srch_arr['shop_name'] =(isset($input['shop_name']) && $input['shop_name'] != '') ? $input['shop_name'] : "";
		$this->srch_arr['user_code'] =(isset($input['user_code']) && $input['user_code'] != '') ? $input['user_code'] : "";
		$this->srch_arr['user_name']= (isset($input['user_name']) && $input['user_name'] != '') ? $input['user_name'] : "";
		$this->srch_arr['user_email']= (isset($input['user_email']) && $input['user_email'] != '') ? $input['user_email'] : "";
		$this->srch_arr['shop_featured']= (isset($input['shop_featured']) && $input['shop_featured'] != '') ? $input['shop_featured'] : "";
	}

	public function getSrchVal($key)
	{
		return (isset($this->srch_arr[$key])) ? $this->srch_arr[$key] : '';
	}

	public function buildShopQuery()
	{
		$this->qry = ShopDetails::leftJoin('users', 'users.id', '=', 'shop_details.user_id')
								->leftJoin('product', function($join)
		                         {
		                             $join->on('users.id', '=', 'product.product_user_id');
		                         })
								->Select("shop_details.*","users.created_at", "users.first_name", "users.last_name", "users.email"
								, "users.activated", "users.id", \DB::raw('count(product.id) as script_cnt'));
		$this->qry->groupBy('users.id');
		$this->qry->Where('users.id', '<>', 0);

		//form the search query
		if($this->getSrchVal('user_code'))
		{
			$this->qry->whereRaw("( users.id = ?  OR users.id =  ? )", array(CUtil::getUserId($this->getSrchVal('user_code')), $this->getSrchVal('user_code')));
		}

		if($this->getSrchVal('user_name'))
		{
			$name_arr = explode(" ",$this->getSrchVal('user_name'));
			if(count($name_arr) > 0)
			{
				foreach($name_arr AS $names)
				{
					$this->qry->whereRaw("( users.first_name LIKE '%".addslashes($names)."%' OR users.last_name LIKE '%".addslashes($names)."%'  )");
				}
			}
		}

		if($this->getSrchVal('shop_name'))
		{
			$name_arr = explode(" ",$this->getSrchVal('shop_name'));
			if(count($name_arr) > 0)
			{
				foreach($name_arr AS $names)
				{
					$this->qry->whereRaw("( shop_details.shop_name LIKE '%".addslashes($names)."%')");
				}
			}
		}

		if($this->getSrchVal('user_email'))
		{
			$this->qry->Where('users.email', $this->getSrchVal('user_email'));
		}

		if($this->getSrchVal('shop_featured'))
		{
			if($this->getSrchVal('shop_featured') == 'Yes')
			{
				$this->qry->Where('shop_details.is_featured_shop', 'Yes');
			}
			else
				$this->qry->Where('shop_details.is_featured_shop', 'No');
		}

		$this->qry->orderBy('users.created_at', 'desc');
		return $this->qry;
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

	public function fetchUserDetails($ident, $type)
	{
		$search_cond ='users.user_id = '.$ident;
		if($type == 'code')
			$search_cond ='users.user_code = '.$ident;

		$user_details = array();
		$user_details['err_msg'] = '';
		$user_details['own_profile'] = 'No';
		$udetails = User::whereRaw($search_cond)
								->first(array('users.first_name', 'users.id', 'users.last_name', 'users.email', 'users.activated',
											'users.activated_at'));

		if(count($udetails) > 0)
		{
			$user_details['user_code'] 		= CUtil::setUserCode($udetails['id']);
			$user_details['email'] 			= $udetails['email'];
			$user_details['user_id'] 		= $user_id = $udetails['id'];
			$user_details['first_name'] 	= $udetails['first_name'];
			$user_details['last_name'] 		= $udetails['last_name'];
			$user_display_name 				= $udetails['first_name'].' '.substr($udetails['last_name'], 0,1);
			$user_details['display_name'] 	= ucwords($user_display_name);
			$user_details['activated_at'] 	= $udetails['activated_at'];
			$user_details['activated'] 	= $udetails['activated'];
		}
		else
		{
			$user_details['err_msg'] = 'No such user found';
		}
		return $user_details;
	}


	public function checkIsValidMember($user_id, $user_type='Member')
	{
		$memberCount = User::where('user_id', $user_id)->count();
		if($memberCount)
			return true;
		return false;
	}

	public function updateShopFeaturedByAdmin($shop_id, $action)
	{
		$shop = ShopDetails::where("id", $shop_id)->first();

		if($shop)
		{
			if(strtolower($action) == 'setfeatured')
			{
				ShopDetails::where("id", $shop_id)->update(array('is_featured_shop'=> 'Yes'));
				$success_msg = trans('webshoppack::admin/manageShops.shoplist_set_featured_suc_msg');
			}
			else//if(strtolower($action) == 'removefeatured')
			{
				ShopDetails::where("id", $shop_id)->update(array('is_featured_shop'=> 'No'));
				$success_msg = trans('webshoppack::admin/manageShops.shoplist_remove_featured_suc_msg');
			}

		}
		return $success_msg;
	}


}