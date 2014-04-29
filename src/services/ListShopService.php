<?php namespace Agriya\Webshoppack;

// @added manikandan_133at10
class ListShopService extends ShopService
{

	public function setListShopsFilterArr()
	{
		$this->filter_arr['owner_name']= '';
		$this->filter_arr['shop_name']= '';
	}

	public function getSrchVal($key)
	{
		return (isset($this->srch_arr[$key])) ? $this->srch_arr[$key] : "";
	}

	public function setListShopsSrchArr($input)
	{
		$this->srch_arr['owner_name']= '';
		$this->srch_arr['shop_name']= '';

		if(isset($input['owner_name']) && $input['owner_name'] != '')
		{
			$this->srch_arr['owner_name']= $input['owner_name'];
		}
		$this->srch_arr['shop_name']= (isset($input['shop_name']) && $input['shop_name'] != '') ? $input['shop_name'] : ((isset($input['q']) && $input['q'] != '') ? $input['q'] : "");
	}

	public function buildShopsListQuery()
	{

		$this->qry = ShopDetails::Select('shop_name', 'shop_details.url_slug', 'shop_details.id', 'shop_city', 'shop_state', 'shop_country', 'users.first_name', 'users.last_name', 'shop_details.user_id')
									->join('users', function($join)
			                         {
			                             $join->on('users.id', '=', 'shop_details.user_id');
			                         });

		if($this->getSrchVal("owner_name") != "")
		{
			$name_arr = explode(" ", $this->getSrchVal('owner_name'));
			if(count($name_arr) > 0)
			{
				foreach($name_arr AS $names)
				{
					$this->qry->whereRaw("( users.first_name LIKE  '%".addslashes($names)."%' OR users.last_name LIKE  '%".addslashes($names)."%')");
				}
			}
		}
		if($this->getSrchVal("shop_name") != "")
		{
			$shop_name = $this->getSrchVal("shop_name");//Input::get("shop_name"); //edited by mohamed_158at11
			$s_name_arr = explode(" ", $shop_name);
			if(count($s_name_arr) > 0)
			{
				foreach($s_name_arr AS $names)
				{
					$this->qry->WhereRaw("(shop_name LIKE '%".addslashes($names)."%')");
				}
			}
		}
		//$this->qry->Where('users.shop_status', 1);
		//$this->qry->Where('users.is_shop_owner', 'Yes');
		$this->qry->groupBy('shop_details.id');
		//$this->qry->orderBy('users.total_products', 'DESC');
		return $this->qry;
	}
}