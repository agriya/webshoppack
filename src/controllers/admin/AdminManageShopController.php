<?php namespace Agriya\Webshoppack;

//@added by manikandan_133at10
class AdminManageShopController extends \BaseController
{
	public function getIndex()
	{
		$this->manageShopService = new AdminManageShopService();
		$d_arr = array();
		$user_list = $user_details = $shop_details = array();

		$this->manageShopService->setShopSrchArr(\Input::All());
		$q = $this->manageShopService->buildShopQuery();
		$perPage	= \Config::get('webshoppack::shop_per_page_list');
		$shop_list 	= $q->paginate($perPage);

		foreach($shop_list AS $shopKey => $shop)
		{
			$shop_details[$shopKey]= $shop;
		}

		$d_arr['allow_change_status'] = true;
		$d_arr['allow_edit_user'] = true;
		$country_arr = $this->manageShopService->getCountryList();
		return \View::make('webshoppack::admin.listShops', compact('d_arr', 'shop_list', 'shop_details','country_arr'));
	}


	public function getChangestatus()
	{
		$this->manageShopService = new AdminManageShopService();

		if(\Input::has('shop_id') && \Input::has('action'))
		{
			$shop_id = \Input::get('shop_id');
			$action = \Input::get('action');
			$success_msg = "";
			//echo "Yes this was called", $user_id," action ", $action;
			$success_msg = $this->manageShopService->updateShopFeaturedByAdmin($shop_id, $action);
		}
		return \Redirect::to(\Config::get('webshoppack::admin_shop_uri'))->with('success_message', $success_msg);
	}

}