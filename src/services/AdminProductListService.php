<?php namespace Agriya\Webshoppack;
//@added by manikandan133at10
class AdminProductListService extends ProductService
{
	private $search_arr = array();
	public $product_status_arr = array();
	private $qry = '';

	public function getFeatureStatusDropOptions()
	{
		$feature_list = array('' => trans('webshoppack::common.select_option'), 'Yes' => trans('webshoppack::common.yes'), 'No' => trans('webshoppack::common.no'));
		return $feature_list;
	}

	public function getProductStatusDropOptions()
	{
		$this->product_status_arr = array('' => trans('webshoppack::common.select_option'),
								'Draft' => trans('webshoppack::admin/productList.product_status_draft'),
								'Ok' => trans('webshoppack::admin/productList.product_status_ok'),
								'ToActivate' => trans('webshoppack::admin/productList.product_status_to_activate'),
								'NotApproved' => trans('webshoppack::admin/productList.product_status_not_approved'),
								'Verified' => trans('webshoppack::admin/productList.product_status_verified')
								);
		return $this->product_status_arr;
	}

	public function getSearchValue($key)
	{
		return (isset($this->search_arr[$key])) ? $this->search_arr[$key] : '';
	}

	public function buildProductsQuery()
	{
		$this->qry = Product::leftJoin('users', 'users.id', '=', 'product.product_user_id')->where('product_status', '!=', 'Deleted');

		//form the search query
		if(is_numeric($this->getSearchValue('search_product_id_from')) && $this->getSearchValue('search_product_id_from') > 0)
		{
			$this->qry->where('product.id', '>=', $this->getSearchValue('search_product_id_from'));
		}
		if(is_numeric($this->getSearchValue('search_product_id_to')) && $this->getSearchValue('search_product_id_to') > 0)
		{
			$this->qry->where('product.id', '<=', $this->getSearchValue('search_product_id_to'));
		}

		# Status
		if($this->getSearchValue('search_product_status') != '')
		{
			$this->qry->where('product.product_status', '=', $this->getSearchValue('search_product_status'));
		}

		# Featured Status
		if($this->getSearchValue('search_featured_product') != '' )
		{
			$this->qry->where('product.is_featured_product', '=', $this->getSearchValue('search_featured_product'));
		}

		# Product title
		if($this->getSearchValue('search_product_name') != '')
		{
			$this->qry->whereRaw("product.product_name LIKE '%".addslashes($this->getSearchValue('search_product_name'))."%'");
		}
		# Author
		if($this->getSearchValue('search_product_author') != '')
		{
			$name_arr = explode(" ",$this->getSearchValue('search_product_author'));
			if(count($name_arr) > 0)
			{
				foreach($name_arr AS $names)
				{
					$this->qry->whereRaw("( users.first_name LIKE '%".addslashes($names)."%' OR users.last_name LIKE '%".addslashes($names)."%' )");
				}
			}

			/*$name_arr = explode(" ",$this->getSrchVal('user_name'));
			if(count($name_arr) > 0)
			{
				foreach($name_arr AS $names)
				{
					$this->qry->whereRaw("( users.first_name LIKE '%".addslashes($names)."%' OR users.last_name LIKE '%".addslashes($names)."%'  )");
				}
			}*/
		}

		# Category
		if($this->getSearchValue('search_product_category') > 0)
		{
			$cat_id_arr = $this->getSubCategoryIds($this->getSearchValue('search_product_category'));
			$this->qry->whereIn('product.product_category_id', $cat_id_arr);
		}

		$this->qry->select( 'product.id', 'product_category_id', 'product_status', 'product_code', 'product_price_currency',
											'product_name', 'product_sold', 'product_user_id', 'product_price',
											'product_discount_price', 'product_discount_fromdate',
											'product_discount_todate', 'is_free_product',
											'is_featured_product', 'product_preview_type', 'product.url_slug');
		return $this->qry;
	}

	public function setProductsSearchArr($input)
	{
		$this->search_arr['search_product_id_from'] =(isset($input['search_product_id_from']) && $input['search_product_id_from'] != '') ? $input['search_product_id_from'] : "";
		$this->search_arr['search_product_id_to']= (isset($input['search_product_id_to']) && $input['search_product_id_to'] != '') ? $input['search_product_id_to'] : "";
		$this->search_arr['search_product_name']= (isset($input['search_product_name']) && $input['search_product_name'] != '') ? $input['search_product_name'] : "";
		$this->search_arr['search_product_category']= (isset($input['search_product_category']) && $input['search_product_category'] != '') ? $input['search_product_category'] : "";
		$this->search_arr['search_featured_product']= (isset($input['search_featured_product']) && $input['search_featured_product'] != '') ? $input['search_featured_product'] : "";
		$this->search_arr['search_product_author']= (isset($input['search_product_author']) && $input['search_product_author'] != '') ? $input['search_product_author'] : "";
		$this->search_arr['search_product_status']= (isset($input['search_product_status']) && $input['search_product_status'] != '') ? $input['search_product_status'] : "";
	}


	public function getProductCategoryArr($cat_id)
    {
		$cat_arr = array();
		$q = \DB::select('SELECT parent.category_name, parent.seo_category_name FROM product_category AS node, product_category AS parent WHERE node.category_left BETWEEN parent.category_left AND parent.category_right  AND node.id = ? ORDER BY node.category_left;', array($cat_id));
		if(count($q) > 0)
		{
			foreach($q AS $cat)
			{
				$cat_arr[$cat->seo_category_name] = $cat->category_name;
			}
			$cat_arr = array_slice($cat_arr, 1); //To remove root category
		}
		return $cat_arr;
	}

	public function activateProduct($p_id, $p_details, $input_arr)
	{
		if(count($p_details) == 0)
		{
			$p_details = Product::whereRaw('id = ?', array($p_id))->first();
		}
		//To update product status to approved
		$affected_rows = Product::where('id', '=', $p_id)->update( array('product_status' => 'Ok', 'date_activated' => date('Y-m-d H:i:s')));
		if($affected_rows)
		{
			//To update user total products count
			/*MpProductService::updateUserTotalProducts($p_details['product_user_id']);
			$input_arr['product_id'] = $p_id;
			$this->addProductStatusComment($input_arr);*/

			//To send mail
			//$this->sendProductActionMail($p_id, 'activate', $input_arr);
			return true;
		}
		return false;
	}

	public function disapproveProduct($p_id, $p_details, $input_arr)
	{
		if(count($p_details) == 0)
		{
			$p_details = Product::whereRaw('id = ?', array($p_id))->first();
		}
		//To update product status to approved
		$affected_rows = Product::where('id', '=', $p_id)->update( array('product_status' => 'NotApproved'));
		if($affected_rows)
		{
			//To send mail
			/*$this->sendProductActionMail($p_id, 'disapprove', $input_arr);
			$input_arr['product_id'] = $p_id;
			$this->addProductStatusComment($input_arr);*/
			return true;
		}
		return false;
	}

	public function deleteProduct($p_id, $p_details)
	{
		if(count($p_details) == 0)
		{
			$p_details = Product::whereRaw('id = ?', array($p_id))->first();
		}
		//To update product status to deleted
		$affected_rows = Product::where('id', '=', $p_id)->update( array('product_status' => 'Deleted'));
		if($affected_rows)
		{
			//To update user total products count
			//MpProductService::updateUserTotalProducts($p_details['product_user_id']);
			return true;
		}
		return false;
	}

	public function changeFeaturedStatus($p_id, $p_details, $status)
	{
		if(count($p_details) == 0)
		{
			$p_details = Product::whereRaw('id = ?', array($p_id))->first();
		}
		$affected_rows = Product::where('id', '=', $p_id)->update( array('is_featured_product' => $status));
		if($affected_rows)
		{
			return true;
		}
		return false;
	}

	public function sendProductActionMail($p_id, $action, $input_arr)
	{
		$product_details = Product::whereRaw('id = ?', array($p_id))->first();
		$user_details = CUtil::getUserDetails($product_details->product_user_id);
		$product_code = $product_details->product_code;
		$url_slug = $product_details->url_slug;
		$view_url = $this->getProductViewURL($product_details->id, $product_details);

		$user_type = (CUtil::isSuperAdmin())? 'Admin':'Staff';
		$logged_user_id = (isLoggedin()) ? getAuthUser()->user_id : 0;
		$staff_details = CUtil::getUserDetails($logged_user_id);

		$data = array(
			'product_code'	=> $product_details['product_code'],
			'product_name'  		=> $product_details['product_name'],
			'display_name'	 => $user_details['display_name'],
			'user_email'	 => $user_details['email'],
			'action'	 => $action,
			'view_url'		=> $view_url,
			'admin_notes'	=> isset($input_arr['comment'])? $input_arr['comment'] : '',
			'user_type'	=> $user_type
		);

		$data['product_details'] = $product_details;
		$data['user_details'] = $user_details;
		$data['staff_details'] = $staff_details;

		//Mail to User
		Mail::send('emails.mp_product.productStatusUpdate', $data, function($m) use ($data) {
			$m->to($data['user_email']);
			$subject = str_replace('VAR_PRODUCT_CODE', $data['product_code'],trans('email.productStatusUpdate'));
			$m->subject($subject);
		});

		//Send mail to admin
		$mailer = new AgMailer;
		$data['subject'] = str_replace('VAR_PRODUCT_CODE', $data['product_code'],trans('email.productStatusUpdateAdmin'));
		$mailer->sendAlertMail('mp_product_status_update', 'emails.mp_product.productStatusUpdateAdmin', $data);
	}

	public function getChangeStatusValidationRule()
	{
		return array('rules' => array('product_status' => 'Required',
										'admin_comment' => 'Required'
										),
										'messages' => array('required' => trans('common.required'))
									);
	}

	public function getStatusDropList($from_status)
	{
		$from_status = strtolower($from_status);
		$status_arr = array('' => trans('webshoppack::common.select_option'));
		$product_status_arr = $this->getProductStatusDropOptions();
		if($from_status == "toactivate")
		{
			$status_arr['activate'] = trans('webshoppack::admin/productList.status_activate');
			$status_arr['disapprove'] = trans('webshoppack::admin/productList.status_disapprove');
		}
		return $status_arr;
	}
}