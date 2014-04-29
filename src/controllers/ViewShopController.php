<?php namespace Agriya\Webshoppack;
use View,Config,URL;
class ViewShopController extends \BaseController
{
    public function getIndex($url_slug)
    {
    	$this->viewShopService = new ViewShopService();

    	//Get user id from url slug
    	$this->viewShopService->setUserId($url_slug);
    	//Get shop details
    	$shop_details = $this->viewShopService->getShopDetails($this->viewShopService->shop_owner_id);
    	//Get shop status
    	$shop_status = $this->viewShopService->getShopStatus();
    	//Get total products of shop owner
    	$this->viewShopService->getTotalProducts();
    	//Get product section details
    	$default_section_details = $this->viewShopService->getDefaultsectionDetails($url_slug);
    	$section_details = $this->viewShopService->getShopProductSectionDetails();

    	$q = $this->viewShopService->getShopProductDetails();
    	$perPage = Config::get('webshoppack::shop_product_per_page_list');
		$product_details = $q->paginate($perPage);

    	$shop_view_url = URL::to(Config::get('webshoppack::shop_uri').'/'.$url_slug);

		$viewShopServiceObj = $this->viewShopService;
		$service_obj = new ProductService;
    	return View::make('webshoppack::viewShop', compact('shop_details', 'shop_status', 'default_section_details', 'section_details', 'shop_view_url', 'viewShopServiceObj', 'service_obj', 'product_details', 'breadcrumb_arr', 'url_slug'));
	}

	/*public function getProductDetails($url_slug)
	{
		//Get user id from url slug
		$service_obj = new ListProductService;
    	$this->viewShopService->setUserId($url_slug);
		$q = $this->viewShopService->getShopProductDetails();
    	$perPage = Config::get('shop.shop_product_per_page_list');;
		$product_details = $q->paginate($perPage);
		return View::make('shop/shopProduct', compact('product_details', 'service_obj', 'url_slug'));
	}*/
}
?>