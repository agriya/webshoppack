<?php namespace Agriya\Webshoppack;
use Input,URL,DB,Config;
class ViewShopService extends ShopService
{
	public $shop_owner_id = 0;
	public $current_user = false;
	public $total_products = 0;

    public function setUserId($url_slug)
    {
    	$user = \Config::get('webshoppack::logged_user_id');
		$this->logged_user_id = $user();

    	$shop_details = ShopDetails::Select('shop_details.user_id', 'shop_details.shop_name')->whereRaw('shop_details.url_slug =  ? AND users.activated = 1', array($url_slug))
						->join('users', 'shop_details.user_id', '=', 'users.id')->first();
		if(count($shop_details) > 0 && $shop_details['shop_name'] != '')
		{
			$this->shop_owner_id = $shop_details['user_id'];
			$this->current_user = (strcmp($this->logged_user_id, $this->shop_owner_id)==0);
		}
	}

	public function getShopStatus()
	{
		$shop_status = UsersShopDetails::where('user_id', $this->shop_owner_id)->pluck('shop_status');
		return $shop_status;
	}

	public function getDefaultsectionDetails($url_slug)
	{
		# assign for all items link
		$default_section_details['id']= '';
		$default_section_details['section_view_url']= URL::to(Config::get('webshoppack::shop_uri').'/'.$url_slug);
		$default_section_details['section_name']= trans("webshoppack::common.all");
		$default_section_details['section_count']= $this->total_products;
		return $default_section_details;
	}

	public function getShopProductSectionDetails()
	{
		$section_details = UserProductSection::whereRaw('user_product_section.user_id =  ? AND prd.product_user_id = ? AND prd.product_status = \'Ok\'', array($this->shop_owner_id, $this->shop_owner_id))
							->join('product AS prd', 'prd.user_section_id', '=', 'user_product_section.id')
							->get(array('user_product_section.id', 'user_product_section.section_name', 'user_product_section.status', 'user_product_section.date_added', DB::raw('COUNT(prd.user_section_id) AS section_count')));
		return $section_details;
	}

	public function getTotalProducts()
	{
		$this->total_products = UsersShopDetails::where('user_id', $this->shop_owner_id)->pluck('total_products');
	}

	public function getShopProductDetails()
	{
		$this->qry = Product::Select(DB::raw("product.id, product.product_status, product.total_downloads, product.url_slug, product.product_user_id, product.product_sold, product.product_added_date,
									   product.product_category_id, product.product_tags, product.is_free_product, product.total_views, product.product_discount_price, product.product_discount_fromdate,
									   product.product_discount_todate, product.product_price, product.product_name, product.product_description, product.product_highlight_text,
									   product.date_activated, NOW() as date_current, IF( ( DATE( NOW() ) BETWEEN product.product_discount_fromdate AND product.product_discount_todate), 1, 0 ) AS have_discount,
									   product.product_price_currency, product.product_price_usd, product.product_discount_price_usd"));
		$this->qry->whereRaw('product.product_status = ? AND product.product_user_id = ?', array('Ok', $this->shop_owner_id));
		if(Input::get("section_id") != "")
		{
			if(is_numeric(Input::get("section_id")))
			{
				$this->qry->join('user_product_section', 'user_product_section.id', '=', 'product.user_section_id')->whereRaw("( user_product_section.id = ".Input::get("section_id")." )");
			}
		}
		$this->qry->orderBy("product.id", 'DESC');
		return $this->qry;
	}
}