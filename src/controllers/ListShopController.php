<?php namespace Agriya\Webshoppack;

class ListShopController extends \BaseController
{
	/**
	 * ListShopController::getIndex()
	 *
	 * @return
	 */
	public function getIndex()
	{
		$this->shopservice = new ListShopService();
		$service_obj = new ProductService;

		$this->shopservice->setListShopsFilterArr();
		$this->shopservice->setListShopsSrchArr(\Input::All());
		$country_arr = $this->shopservice->getCountryList();
		$q = $this->shopservice->buildShopsListQuery();
		$perPage	= 10;
		$shops_list 	= $q->paginate($perPage);
		return \View::make('webshoppack::listShops', compact('shops_list', 'country_arr', 'service_obj'));
	}
}