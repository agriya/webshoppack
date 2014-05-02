<?php namespace Agriya\Webshoppack;

use Agriya\Webshoppack\CUtil as CUtill;
use Agriya\Webshoppack\ProductService as ProductService;
class Webshoppack {

  	public static function greeting(){
    	return "What up dawg Webshoppack";
  	}
  	public static function getUserDetails($user_id)
	{
		return CUtill::getUserDetails($user_id);
	}
	public static function getShopDetails($user_id)
	{
		$ProductService = new ProductService;
		return $ProductService->getShopDetails($user_id);
	}
	public static function fetchShopItems($shop_user_id, $current_p_id = 0, $limit = 5)
	{
		$ProductService = new ProductService;
		return $ProductService->fetchShopItems($shop_user_id, $current_p_id, $limit);
	}
	public static function getProductShopURL($id, $shop_details = array())
	{
		$ProductService = new ProductService;
		return $ProductService->getProductShopURL($id, $shop_details);
	}

	static public function populateProductDefaultThumbImages($p_id)
	{
		$ProductService = new ProductService;
		return $ProductService->populateProductDefaultThumbImages($p_id);
	}
	public static function getProductDefaultThumbImage($p_id, $image_size = "thumb", $p_image_info = array())
	{
		$ProductService = new ProductService;
		return $ProductService->getProductDefaultThumbImage($p_id, $image_size, $p_image_info);

	}
	public static function getProductViewURL($p_id, $p_details = array())
	{
		$ProductService = new ProductService;
		return $ProductService->getProductViewURL($p_id, $p_details);
	}
	public static function getShopDetailsView($user_id = null, $load_view = false)
	{
		if(is_null($user_id))
			return '';
		$d_arr = array();
		$d_arr['shop_details'] = Webshoppack::getShopDetails($user_id);
		$d_arr['shop_product_list'] = Webshoppack::fetchShopItems($user_id, 3);
		$d_arr['shop_url'] = Webshoppack::getProductShopURL($d_arr['shop_details']['id'], $d_arr['shop_details']);
		if($load_view)
			return \View::make('webshoppack::showShop', compact('d_arr'));
		else
			return $d_arr;

	}

}