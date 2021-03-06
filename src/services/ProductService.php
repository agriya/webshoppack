<?php namespace Agriya\Webshoppack;

class ProductService
{
	public $p_tab_lang_arr = array();
	public $root_category_id = 0;
	public $prod_cat_count_arr = array();
	public $p_tab_arr = array();
	public $alert_message = '';
	public $validate_tab_arr = array();
	public $product_media_type = '';
	public $product_max_upload_size = 0;
	public $allowed_upload_formats = '';
	# space displayed before category name in category list drop down for ease identification of sub-categories
	const MAX_CATEGORY_SPACING = 4;
	private $srch_arr = array();

	function __construct()
	{
		$this->p_tab_arr = array('basic' => false, 'price' => false, 'attribute' => false, 'preview_files' => false, 'download_files' => false, 'publish' => false);
		$this->initProductTabList();
		$user = \Config::get('webshoppack::logged_user_id');
		$this->logged_user_id = $user();
    }

    public function getCookie($cookie_name)
	{
		$value = "";
		if(\Cookie::has($cookie_name) && \Cookie::get($cookie_name)!=null)
		{
			$value = \Cookie::get($cookie_name);
		}
		return $value;
	}
	public function getShopDetails($user_id)
	{
		$shop_arr = ShopDetails::whereRaw('user_id = ?', array($user_id))->first(array('id', 'shop_name', 'url_slug', 'shop_slogan'));
		if(count($shop_arr) > 0)
		{
			$shop_arr['shop_url'] = $this->getProductShopURL($shop_arr['id'],$shop_arr);
		}
		return $shop_arr;
	}

	public function fetchSliderDefaultImage($p_id)
	{
		$result_arr = array();
		$p_default_arr = ProductImage::where('product_id', '=', $p_id)
										->first( array('default_title', 'default_img', 'default_ext', 'default_width', 'default_height', 'default_orig_img_width', 'default_orig_img_height'));
		$cfg_large_width = \Config::get("webshoppack::photos_large_width");
		$cfg_large_height = \Config::get("webshoppack::photos_large_height");
		$cfg_thumb_width = \Config::get("webshoppack::photos_thumb_width");
		$cfg_thumb_height = \Config::get("webshoppack::photos_thumb_height");

		if(count($p_default_arr) > 0 && isset($p_default_arr['default_title']) && $p_default_arr['default_title'] != "")
		{
			$img_path = \URL::asset(\Config::get("webshoppack::photos_folder"))."/";
			$large_img_attr = CUtil::TPL_DISP_IMAGE($cfg_large_width, $cfg_large_height, $p_default_arr["default_width"], $p_default_arr["default_height"]);
			$thumb_img_attr = CUtil::TPL_DISP_IMAGE($cfg_thumb_width, $cfg_thumb_height, $p_default_arr["default_width"], $p_default_arr["default_height"]);

			$result_arr = array('title' => $p_default_arr['default_title'],
									'img_name' => $p_default_arr['default_img'],
									'img_ext' => $p_default_arr['default_ext'],
									'large_img_path' => $img_path . $p_default_arr["default_img"]."L.".$p_default_arr["default_ext"],
									'large_img_attr' => $large_img_attr,
									'thumb_img_path' => $img_path . $p_default_arr["default_img"]."T.".$p_default_arr["default_ext"],
									'thumb_img_attr' => $thumb_img_attr,
									'orig_img_path' => $img_path . $p_default_arr["default_img"].".".$p_default_arr["default_ext"],
									'default_orig_img_width' => $p_default_arr['default_orig_img_width'],
									'default_orig_img_height' => $p_default_arr['default_orig_img_height'],
									'image_exits' => true,
									);
		}
		else
		{
			$result_arr = array('title' => trans('webshoppack::viewProduct.no_image'),
									'img_name' => '',
									'img_ext' => '',
									'orig_img_path' => '',
									'large_img_path' => \URL::asset("packages/agriya/webshoppack/images/no_image").'/'.\Config::get("webshoppack::photos_large_no_image"),
									'large_img_attr' => CUtil::TPL_DISP_IMAGE($cfg_large_width, $cfg_large_height, $cfg_large_width, $cfg_large_height),
									'thumb_img_path' => \URL::asset("packages/agriya/webshoppack/images/no_image").'/'.\Config::get("webshoppack::photos_thumb_no_image"),
									'thumb_img_attr' => CUtil::TPL_DISP_IMAGE($cfg_thumb_width, $cfg_thumb_height, $cfg_thumb_width, $cfg_thumb_height),
									'default_orig_img_width' => $p_default_arr['default_orig_img_width'],
									'default_orig_img_height' => $p_default_arr['default_orig_img_height'],
									'image_exits' => false,

									);

		}
		return $result_arr;
	}
	public function fetchSliderPreviewImage($p_id)
	{
		$result_arr = array();
		$preview_arr = ProductResource::where('product_id', '=', $p_id)
										->where('resource_type', '=', 'Image')
										->orderBy('display_order')
										->get( array('filename', 'ext', 'title', 'width', 'height', 'l_width', 'l_height', 't_width', 't_height'));
		$cfg_large_width = \Config::get("webshoppack::photos_large_width");
		$cfg_large_height = \Config::get("webshoppack::photos_large_height");
		$cfg_thumb_width = \Config::get("webshoppack::photos_thumb_width");
		$cfg_thumb_height = \Config::get("webshoppack::photos_thumb_height");

		if(count($preview_arr) > 0)
		{
			$img_path = \URL::asset(\Config::get("webshoppack::photos_folder"))."/";
			foreach($preview_arr AS $img)
			{
				$large_img_attr = CUtil::TPL_DISP_IMAGE($cfg_large_width, $cfg_large_height, $img["l_width"], $img["l_height"]);
				$thumb_img_attr = CUtil::TPL_DISP_IMAGE($cfg_thumb_width, $cfg_thumb_height, $img["t_width"], $img["t_height"]);

				$result_arr[] = array('title' => $img['title'],
										'img_name' => $img['filename'],
										'img_ext' => $img['ext'],
										'large_img_path' => $img_path . $img["filename"]."L.".$img["ext"],
										'large_img_attr' => $large_img_attr,
										'thumb_img_path' => $img_path . $img["filename"]."T.".$img["ext"],
										'thumb_img_attr' => $thumb_img_attr,
										'orig_img_path' => $img_path . $img["filename"].".".$img["ext"],
										'width' => $img['width'],
										'height' => $img['height'],
										);
			}
		}
		return $result_arr;
	}

    public function initProductTabList()
	{
		$this->p_tab_lang_arr = array('basic' => trans('webshoppack::product.basic_tab'),
									  'price' => trans('webshoppack::product.price_tab'),
									  'meta' => trans('webshoppack::product.meta_tab'),
									  'attribute' => trans('webshoppack::product.attribute_tab'),
									  'preview_files' => trans('webshoppack::product.preview_files_tab'),
									  'download_files' => trans('webshoppack::product.download_files_tab'),
									  'publish' => trans('webshoppack::product.publish_tab'),
									  'status' => trans('webshoppack::product.approval_status_tab')
									);
	}

	public function getRootCategoryId()
	{
		$root_cat = ProductCategory::Select('id')->whereRaw('category_level = 0 AND parent_category_id = 0')->first();
		if(count($root_cat) > 0)
		{
			$this->root_category_id = $root_cat['id'];
		}
		return $this->root_category_id;
	}

	public function getCategoriesList($cat_id = '')
	{
		$cat_id = ($cat_id == '' || $cat_id == 0) ? $this->root_category_id : $cat_id;
		$cat_list_arr = array();
		$cat_arr = ProductCategory::whereRaw('parent_category_id = ?', array($cat_id))->orderBy('category_left', 'ASC')->get(array('id', 'category_name'));
		foreach($cat_arr AS $cat)
		{
			$cat_list_arr[$cat->id] = $cat->category_name;
		}
		return $cat_list_arr;
	}

	public function getCountForProducts()
	{
		//AND u.user_status = \'Ok\'
		$user_table = \Config::get('webshoppack::user_table');
		$user_id = 'u.'.\Config::get('webshoppack::user_id_field');
		$product_details = \DB::select('select parent.id AS category_id, COUNT(prod.id) product_count from product_category AS node, product_category AS parent,
										product AS prod, '.$user_table.'  AS u where node.category_left BETWEEN parent.category_left AND parent.category_right
										AND node.id = prod.product_category_id AND prod.product_status != \'Deleted\' AND prod.product_status = \'Ok\' AND '.$user_id.' = prod.product_user_id
										GROUP BY parent.id ORDER BY node.category_left');
		if (count($product_details)) {
			foreach($product_details as $product)
			{
				$this->prod_cat_count_arr[$product->category_id] = $product->product_count;
			}
		}
	}

	public function populateProductCategoryList($cat_id = '')
	{
		$catList = array();
		$cat_id = ($cat_id == '' || $cat_id == 0) ? $this->root_category_id : $cat_id;

		$cat_details = ProductCategory::whereRaw('parent_category_id = ?', array($cat_id))->orderBy('category_left', 'ASC')->get(array('id', 'category_name', 'seo_category_name'));
		if(count($cat_details) > 0)
		{
			foreach($cat_details as $catkey => $cat)
			{
				$catList[$catkey] = $cat;
				$count = isset($this->prod_cat_count_arr[$cat['id']]) ? $this->prod_cat_count_arr[$cat['id']] : 0;
				$catList[$catkey]['cat_id'] = $cat['id'];
				$catList[$catkey]['product_count'] = $count;
				$catList[$catkey]['cat_link'] = $this->urlLink($cat['seo_category_name']);
			}
		}
		return $catList;
	}

	public function urlLink($values)
	{
		$qryString = '';
		$current_script = \URL::full();
		$parts = parse_url($current_script);
		$qryPart = parse_url($_SERVER['REQUEST_URI']);

		$concat_slash = '/';
		$parts['path'] = $parts['path'].$concat_slash.$values;

		$port = '';
		if(isset($parts['port']) && $parts['port'] != "")
		{
			$port = ':'.$parts['port'];
		}

		if($qryString!="" && $qryString!="?")
			$newUrl = $parts['scheme'].'://'.$parts['host'].$port.$parts['path'].'/'.$qryString;
		else
			$newUrl = $parts['scheme'].'://'.$parts['host'].$port.$parts['path'];
		return $newUrl;
	}
	public function getProductCode($seo_title)
	{
		$product_code = '';
		$matches = null;
		preg_match('/^(P[0-9]{6})\-/', $seo_title, $matches);
		if (!isset($matches[1])) {
			preg_match('/^(P[0-9]{6})$/', $seo_title, $matches);
		}
		if (isset($matches[1])){
			$product_code = $matches[1];
		}
		return $product_code;
	}

	public function buildProductQuery($cat_id)
	{
		$user_first_name = \Config::get('webshoppack::user_table').'.'.\Config::get('webshoppack::user_fields')['fname'];
		$user_last_name = \Config::get('webshoppack::user_table').'.'.\Config::get('webshoppack::user_fields')['lname'];
		$this->applicable_cats_ids = array();
		$search_product_tags_sql = "";

		if(\Input::has('tag_search') && \Input::get('tag_search') != '')
		{
			$tagsearch_list = $this->remExcludeValuesFromSearchTags(\Input::get('tag_search'));
			$this->exclude_tags = $tagsearch_list;
			if(!empty($this->exclude_tags))
			{
				$excludetags_count = COUNT($this->exclude_tags);
				$search_product_tags_sql = ', SUM(';
				foreach($this->exclude_tags as $exclude_key => $exclude_val)
				{
					$priority_val = $excludetags_count - $exclude_key;
					$search_product_tags_sql.= "
						IF( product.product_name LIKE '%".addslashes($exclude_val)."%', ".$priority_val."*5, 0 )  +
						IF( product.product_tags LIKE '%".addslashes($exclude_val)."%', ".$priority_val."*3, 0 ) +
						IF( product.product_description LIKE '%".addslashes($exclude_val)."%', ".$priority_val."*1, 0 ) + ";
				}
				$search_product_tags_sql .= '0 ) AS relevant_count';
			}
		}

		$this->qry = Product::Select(\DB::raw("product.id, product.product_status, product.url_slug, product.product_user_id, product.product_sold, product.product_added_date,
									   product.product_category_id, product.product_tags, product.is_free_product, product.total_views, product.product_discount_price, product.product_discount_fromdate,
									   product.product_discount_todate, product.product_price, product.product_name, product.product_description, product.product_highlight_text, product.demo_url, product.product_code,
									   product_category.parent_category_id, product.date_activated, NOW() as date_current, IF( ( DATE( NOW() ) BETWEEN product.product_discount_fromdate AND product.product_discount_todate), 1, 0 ) AS have_discount,
									   product.product_price_currency, product.product_price_usd, product.product_discount_price_usd".$search_product_tags_sql));
		$this->qry->join(\Config::get('webshoppack::user_table'), 'product.product_user_id', '=', \Config::get('webshoppack::user_table').'.'.\Config::get('webshoppack::user_id_field'));
		$this->qry->join('product_category', 'product.product_category_id', '=', 'product_category.id');
		$this->qry->LeftJoin('shop_details', 'product.product_user_id', '=', 'shop_details.user_id');
		$this->qry->Where('product.product_status', '=', 'Ok');

		if($cat_id > 0)
		{
			$search_category_array = array($cat_id);
		}

		if(\Input::has('cat_search') && \Input::get('cat_search') != '')
		{
			$search_category_array = \Input::get('cat_search');
		}

		if(isset($search_category_array) && count($search_category_array) > 0)
		{
			foreach($search_category_array as $c_id)
			{
				//select the applicable categories to which the items may belong ..
			 	$sub_cat_ids = $this->getSubCategoryIdsForProduct($c_id);
				$this->applicable_cats_ids =  array_unique(array_merge($this->applicable_cats_ids, $sub_cat_ids));
			}
			if(count($this->applicable_cats_ids))
			{
				$this->qry->whereRaw(\DB::raw('product.product_category_id IN (\''.implode( '\',\'', $this->applicable_cats_ids).'\')'));
			}
		}

		if(\Input::has('author_search') && \Input::get('author_search') != '')
		{
			$name_arr = explode(" ", \Input::get('author_search'));
			if(count($name_arr) > 0)
			{
				foreach($name_arr AS $names)
				{
					$this->qry->whereRaw("(". $user_first_name ." LIKE '%".addslashes($names)."%' OR ". $user_last_name ." LIKE '%".addslashes($names)."%')");
				}
			}
		}

		if(\Input::has('price_range_start') OR \Input::has('price_range_end'))
		{
			$start_price = \Input::get('price_range_start');
			$end_price = \Input::get('price_range_end');
			$start_price = is_numeric($start_price) ? $start_price : 0;
			$end_price = is_numeric($end_price) ? $end_price : 0;

			//$start_price = CUtil::convertBaseCurrencyToUSD($start_price, $user_currency);
			//$end_price = CUtil::convertBaseCurrencyToUSD($end_price, $user_currency);

			$condn_to_check_discount = '((DATE(NOW()) BETWEEN product.product_discount_fromdate AND product.product_discount_todate) AND product.product_discount_price)';
			if($start_price != '' AND $end_price != '')
			{
				$this->qry->whereRaw(\DB::raw('(IF('.$condn_to_check_discount.','.
										'(product.product_discount_price_usd  BETWEEN '.$start_price.' AND '.$end_price.'),'.
										'(product.product_price_usd BETWEEN '.$start_price.' AND '.$end_price.')))'.
										' AND product.is_free_product = \'No\''));
			}
			elseif($start_price AND !$end_price)
			{
				$this->qry->whereRaw(\DB::raw('(IF('.$condn_to_check_discount.','.
										'(product.product_discount_price_usd >= '.$start_price.'),'.
										'(product.product_price_usd >= '.$start_price.')))'.
										' AND product.is_free_product = \'No\''));
			}
			elseif(!$start_price AND $end_price)
			{
				$this->qry->whereRaw(\DB::raw('(IF('.$condn_to_check_discount.','.
										'(product.product_discount_price_usd <= '.$end_price.'),'.
										'(product.product_price_usd <= '.$end_price.')))'.
										' AND product.is_free_product = \'No\''));
			}
		}

		if(\Input::has('tag_search') && \Input::get('tag_search') != '')
		{
			$tags_condition = '';
			$tagsearch_list = $this->exclude_tags;

			if(!empty($tagsearch_list) and COUNT($tagsearch_list) > 0)
			{
				  if(\Config::get('webshoppack::product_search_include_title'))
				  {
					foreach($tagsearch_list as $tag_key => $tag_val)
					{
						if($tags_condition != "") {
							$tags_condition .= " OR ";
						}

						$tags_condition .= "((product.product_tags LIKE '%".addslashes($tag_val)."%') OR (product.product_name LIKE '%".addslashes($tag_val)."%')
											OR (product.product_description LIKE '%".addslashes($tag_val)."%') )";
					}
					if($tags_condition != '') {
						$this->qry->whereRaw(\DB::raw("(".$tags_condition.")"));
					}
				  }
			  }
		}

		$this->order_by_field = $this->order_by;
		if($this->order_by == 'id')
		{
			$this->order_by_field = 'date_activated';
		}
		if($this->order_by == 'product_sold')
		{
			$this->qry->whereRaw("(product.is_free_product = 'No' AND product.product_price_usd != 0 AND  product.product_price_usd != '' ) AND (product.product_sold > 0)");
		}
		if($this->order_by == 'featured')
		{
			$this->order_by_field = 'date_activated';
			$this->qry->Where('product.is_featured_product', '=', 'Yes');
		}
		if($this->order_by == 'is_free_product')
		{
			$this->qry->whereRaw(" ( product.is_free_product = 'Yes' OR  (product.product_price_usd = 0 OR product.product_price_usd = '' ) )");
		}

		$this->qry->groupBy('product.id');
		$this->qry->orderBy($this->order_by_field, 'DESC');
		return $this->qry;
	}

	public function getSubCategoryIdsForProduct($category_id)
	{
		$sub_cat_ids = array($category_id);
		$sub_cat_details = \DB::select('select node.id AS sub_category_id from product_category node, product_category parent where
				node.category_left BETWEEN parent.category_left AND parent.category_right AND parent.id = ? ORDER BY node.category_left', array($category_id));
		if (count($sub_cat_details) > 0)
		{
			foreach($sub_cat_details as $sub_cat)
			{
				$sub_cat_ids[] = $sub_cat->sub_category_id;
			}
		}
		return $sub_cat_ids;
	}

	public function getCategoryName($cat_id)
	{
		$category_name = '';
		$cat_info = ProductCategory::Select('category_name')->whereRaw('id = ?', array($cat_id))->first();
		if(count($cat_info) > 0)
		{
			$category_name = $cat_info['category_name'];
		}
		return $category_name;
	}

	public function getProductSectionDropList($user_id = 0)
	{
		$section_list_arr = array('' => trans('webshoppack::common.select_option'));
		$q = UserProductSection::where('status', '=', 'Yes');
		if($user_id > 0)
		{
			$q->where('user_id', '=', $user_id);
		}
		$section_arr = $q->get();
		foreach($section_arr AS $value)
		{
			$section_list_arr[$value->id] = $value->section_name;
		}
		return $section_list_arr;
	}

	public function getCategoryListArr()
	{
		$r_arr = array('' => trans('webshoppack::common.select_option'));
		$d_arr = ProductCategory::where('status', '=', 'active')->where('category_level', '=', 1)->orderBy('category_left', 'ASC')
								->get(array('id', 'category_name', 'category_level'))
								->toArray();
		foreach($d_arr AS $val)
		{
			if($val['category_level'] == 0)
			{
				$this->root_category_id = $val['id'];
			}
			$r_arr[$val['id']] = $val['category_name'];
		}
		return $r_arr;
	}

	public function getAllTopLevelCategoryIds($category_id = 0)
	{
		$q = \DB::select('SELECT group_concat(parent.id ORDER BY parent.category_left) as category_ids from product_category AS node, product_category AS parent where node.category_left  BETWEEN parent.category_left AND parent.category_right AND node.id = ?', array($category_id));
		return $q[0]->category_ids;
	}

	public function getTabList($p_id, $input_arr = array(), $action = 'add')
	{
		 if(count($input_arr) > 0)
		 {
			$p_id = ($p_id == '')? 0 : $p_id;
			//check prodcut category has attributes..
			if(isset($input_arr['product_category_id'])) //No need to check for add product page
			{
				 $has_attr_tab = $this->checkProductHasAttributeTab($input_arr['product_category_id']);
				 if(!$has_attr_tab)
				 {
				 	unset($this->p_tab_arr['attribute']);
				 }
			}
			//Check download option are avalilable for this product
			if(isset($input_arr['is_downloadable_product']) && $input_arr['is_downloadable_product'] == 'No')
			{
				unset($this->p_tab_arr['download_files']);
			}
		 	if($action == 'add')
		 	{
			 	$this->p_tab_arr['basic'] = true;
			}
			else
			{
				$prev_value = false; //To check, if previous value are true, then make it next tab are visible
				foreach($this->p_tab_arr AS $key => $name)
			 	{
					if(\Config::get("webshoppack::product_tab_validation"))
					{
						if($key == 'download_files')
						{
							if($this->validateDownloadTab($p_id))
							{
								$prev_value = $this->p_tab_arr[$key] = true;
							}
							else
							{
								if($prev_value)
								{
									$this->p_tab_arr[$key] = true;
								}
								break;
							}
						}
						else
						{
							$validator_arr = $this->getproductValidation($input_arr, $p_id, $key);
							$validator = Validator::make($input_arr, $validator_arr['rules'], $validator_arr['messages']);
							if($validator->passes())
							{
								$prev_value = $this->p_tab_arr[$key] = true;
							}
							else
							{
								if($prev_value)
								{
									$this->p_tab_arr[$key] = true;
								}
								break;
							}
						}
						$prev_value = $this->p_tab_arr;
					}
					else
					{
						$this->p_tab_arr[$key] = true;
					}
				}
			}
		 }
		 return $this->p_tab_arr;
	}

	public function getSubCategoryList($category_id)
	{
		$r_arr = array('' => trans('webshoppack::common.select_option'));
		$d_arr = ProductCategory::where('status', '=', 'active')->where('parent_category_id', '=', $category_id)->orderBy('category_left', 'ASC')
				->get(array('id', 'category_name'))->toArray();
		foreach($d_arr AS $val)
		{
			$r_arr[$val['id']] = $val['category_name'];
		}
		return $r_arr;
	}

	public function getSectionNameValidation($id = 0)
    {
		$rules_arr = array('section_name' => 'Required|unique:user_product_section,section_name,'.$id);
		$message_arr = array('section_name.unique' => trans("webshoppack::product.section_already_exists"));
		return array('rules' => $rules_arr, 'messages' => $message_arr);
	}

	public function addSectionName($input_arr)
	{
		$user_section_id = '';
		$user_id = '';
		if(count($input_arr) > 0)
		{
			$data_arr = array('user_id' => $this->logged_user_id,
	                            'section_name' => $input_arr['section_name'],
	                            'status' => 'Yes',
	                            'date_added' => date('Y-m-d H:i:s'),
	                         );
			$user_section_id = UserProductSection::insertGetId($data_arr);
		    return $user_section_id;
		}
		return $user_section_id;
	}

	public function getproductValidation($input_arr, $id = 0, $tab = 'basic')
    {
		$rules_arr = $message_arr = array();
		if($tab == 'basic')
		{
			$rules_arr = array('product_name' => 'Required|min:'.\Config::get("webshoppack::title_min_length").'|max:'.\Config::get("webshoppack::title_max_length"),
								'product_category_id' => 'Required',
								'product_tags' => 'Required',
								'product_highlight_text' => 'max:'.\Config::get("webshoppack::summary_max_length"),
								'demo_url' => 'url',
			);
			//To validate section, only if input from user form
			if(\Input::has('user_section_id'))
			{
				$rules_arr['user_section_id'] = 'exists:user_product_section,id,user_id,'.$this->logged_user_id;
			}
			$message_arr = array('section_name.unique' => trans("webshoppack::product.section_already_exists"));
		}
		elseif($tab == 'price')
		{
			$is_free_product = isset($input_arr['is_free_product'])? $input_arr['is_free_product']: 'No';
			if($is_free_product != 'Yes')
			{
				$rules_arr = array('product_price' => 'Required|IsValidPrice|numeric|Min:1',
								'product_discount_price' => 'IsValidPrice|numeric|Max:'.$input_arr['product_price']
							);
				if($input_arr['product_discount_price'] > 0)
				{
					$date_format = 'd/m/Y';
					if($input_arr['product_discount_fromdate'] != '0000-00-00')
					{
						$rules_arr['product_discount_fromdate'] = 'Required|date_format:VAR_DATE_FORMAT';
					}
					if($input_arr['product_discount_todate'] != '0000-00-00' && $input_arr['product_discount_fromdate'] != '0000-00-00')
					{
						//check validation from database?..
						$from_date = str_replace('/', '-', $input_arr['product_discount_fromdate']);
						$from_date = date('Y-m-d', strtotime($from_date));

						$to_date = str_replace('/', '-', $input_arr['product_discount_todate']);
						$to_date = date('Y-m-d', strtotime($to_date));
						$rules_arr['product_discount_todate'] = 'Required|date_format:VAR_DATE_FORMAT|CustAfter:'.$from_date.','.$to_date;
						//To replace the datre format
						$rules_arr['product_discount_fromdate'] = str_replace('VAR_DATE_FORMAT', $date_format, $rules_arr['product_discount_fromdate']);
						$rules_arr['product_discount_todate'] = str_replace('VAR_DATE_FORMAT', $date_format, $rules_arr['product_discount_todate']);
					}
				}
				$message_arr = array('product_price.is_valid_price' => trans("webshoppack::product.invalid_product_price"),
									'product_discount_price.is_valid_price' => trans("webshoppack::product.invalid_product_price"),
									'product_price.min' => trans("webshoppack::product.err_tip_greater_than_zero"),
									'product_discount_price.max' => trans("webshoppack::product.invalid_product_discount_price"),
									'product_discount_todate.cust_after' => trans("webshoppack::product.invalid_product_discount_todate"),
									'date_format' => trans("webshoppack::product.invalid_date_format"),
									'required' => trans('webshoppack::common.required')
								);
			}
		}
		elseif($tab == 'attribute')
		{
			if(isset($input_arr['product_category_id']) && is_numeric($input_arr['product_category_id']))
			{
				$attr_arr = $this->getAttributesList($input_arr['product_category_id']);
				foreach($attr_arr AS $key => $val)
				{
					$id = $val['attribute_id'];
					$key = 'attribute_'.$id;
					if($val['validation_rules'] != '')
					{
						$rule_str = str_replace('minlength-', 'min:', $val['validation_rules']);
						$rule_str = str_replace('maxlength-', 'max:', $rule_str);
						$rules_arr[$key] = $rule_str;
						$message_arr[$key.'.required'] = trans('webshoppack::common.required');
						$message_arr[$key.'.alpha'] = trans("webshoppack::product.alpha_only");
						$message_arr[$key.'.numeric'] = trans("webshoppack::product.numeric_only");
					}
				}
			}
		}
		elseif($tab == 'publish')
		{
			$rules_arr = array('delivery_days' => 'numeric');
		}
		return array('rules' => $rules_arr, 'messages' => $message_arr);
	}

	public function addProduct($input_arr)
	{
		$p_id = 0;
		if(count($input_arr) > 0 )
		{
			$product_code = CUtil::generateRandomUniqueCode('P', 'product', 'product_code');
			$url_slug = \Str::slug($input_arr['product_name']);
			$data_arr = array('product_code' => $product_code,
		                      'product_name' => $input_arr['product_name'],
		                      'product_description' => $input_arr['product_description'],
		                      'product_support_content' => $input_arr['product_support_content'],
		                      'meta_title' => $input_arr['meta_title'],
		                      'meta_keyword' => $input_arr['meta_keyword'],
		                      'meta_description' => $input_arr['meta_description'],
		                      'product_highlight_text' => $input_arr['product_highlight_text'],
		                      'demo_url' => $input_arr['demo_url'],
		                      'demo_details' => $input_arr['demo_details'],
		                      'product_tags' => $input_arr['product_tags'],
		                      'user_section_id' => $input_arr['user_section_id'],
		                      'product_status' => 'Draft',
		                      'product_price_currency' => \Config::get('webshoppack::site_default_currency'), //Make default USD format currency
		                      'global_transaction_fee_used' => 'Yes',
		                      'product_category_id' => $input_arr['my_category_id'],
		                      'url_slug' => isset($input_arr['url_slug'])? $input_arr['url_slug'] : $url_slug,
		                      'product_added_date' => \DB::raw('NOW()'),
		                      'last_updated_date' => \DB::raw('NOW()'),
		                      'product_user_id' => $this->logged_user_id);

			$p_id = Product::insertGetId($data_arr);

			//To add dumb data for product image
			$p_img_arr = array('product_id' => $p_id);
			$p_img_id = ProductImage::insertGetId($p_img_arr);

		}
		return $p_id;
	}

	public function checkProductHasAttributeTab($category_id)
	{
		$category_ids = $this->getAllTopLevelCategoryIds($category_id);
		$cat_arr = explode(',', $category_ids);
		if(count($cat_arr) > 0)
		{
			$a_count = ProductCategoryAttributes::whereIn('category_id', $cat_arr)->count();
			if($a_count > 0)
			{
				return true;
			}
		}
		return false;
	}

	public function validateDownloadTab($p_id)
	{
		if(\Config::get('webshoppack::download_files_is_mandatory'))
		{
			$count = ProductResource::whereRaw('product_id = ? AND resource_type = ?', array($p_id, 'Archive'))->count();
			return ($count == 0) ? false : true;
		}
		return true;
	}

	public static function populateDateCalendar($textbox_id, $starts_from_date = '', $calendar_params_array = array())
	{

		if($starts_from_date != "")
		{
			$serverdate =  date('m-d-Y', strtotime($starts_from_date));
		}
		else
			$serverdate =  date("m-d-Y");
		$init_params_array  =  array('dateFormat' => 'mm-dd-yy',
									 'showOn'     => 'both',
									 'buttonText' => '...',
									 'changeMonth'=> 'true',
									 'changeYear' => 'true',
									 'minDate'    => $serverdate,
									 'defaultDate' => $serverdate,
									 'maxDate'    => '+1y',
									 'yearRange'  => '',
									 'onSelect'	  => ''
									);
		//if the parameters  are not , set the default ones ..
		foreach($init_params_array as $key => $value)
		{
			if(!isset($calendar_params_array[$key]))
			{
				$calendar_params_array[$key] = $value;
			}
		}
		$date_res = '';
		if ($calendar_params_array['minDate'] != '')
		{
			$date_res = ','.'minDate: \''.$calendar_params_array['minDate'].'\'';
		}
		if ($calendar_params_array['maxDate'] != '')
		{
			$date_res .= ','.'maxDate: \''.$calendar_params_array['maxDate'].'\'';
		}
		if ($calendar_params_array['yearRange'] != '')
		{
			$date_res .= ','.'yearRange: \''.$calendar_params_array['yearRange'].'\'';
		}
		if ($calendar_params_array['onSelect'] != '')
		{
			$date_res .= ','.'onSelect: \''.$calendar_params_array['onSelect'].'\'';
		}


?>
		<script type="text/javascript">
			var monthNamesShort_arr = new Array(
				"<?php echo trans('webshoppack::common.january_short') ?>",
				"<?php echo trans('webshoppack::common.february_short') ?>",
				"<?php echo trans('webshoppack::common.march_short') ?>",
				"<?php echo trans('webshoppack::common.april_short') ?>",
				"<?php echo trans('webshoppack::common.may_short') ?>",
				"<?php echo trans('webshoppack::common.june_short') ?>",
				"<?php echo trans('webshoppack::common.july_short') ?>",
				"<?php echo trans('webshoppack::common.august_short') ?>",
				"<?php echo trans('webshoppack::common.september_short') ?>",
				"<?php echo trans('webshoppack::common.october_short') ?>",
				"<?php echo trans('webshoppack::common.november_short') ?>",
				"<?php echo trans('webshoppack::common.december_short') ?>"
			);

			$(function() {
				$('#<?php echo $textbox_id?>').datepicker({
					closeText: "<?php echo trans('webshoppack::common.js.datepicker_closeText') ?>",
					prevText: "<?php echo trans('webshoppack::common.js.datepicker_prevText') ?>",
					nextText: "<?php echo trans('webshoppack::common.js.datepicker_nextText') ?>",
					monthNamesShort: monthNamesShort_arr,
					duration:'fast',
					dateFormat: '<?php echo $calendar_params_array['dateFormat']?>',
					showOn: '<?php echo $calendar_params_array['showOn']?>',
					buttonText: '<?php echo $calendar_params_array['buttonText']?>',
					changeMonth: '<?php echo $calendar_params_array['changeMonth']?>',
					changeYear: '<?php echo $calendar_params_array['changeYear']?>',
					defaultDate: '<?php echo $calendar_params_array['defaultDate'] ?>'
					<?php echo $date_res; ?>
				});
		});
		</script>
<?php
	}

	public function updateProduct($input_arr, $tab = 'basic')
	{
		$return_arr = array('status' => false, 'validate_tab_arr' => array(), 'final_success' => false);
		if(count($input_arr) > 0)
		{
			$data_arr = array();
			if($tab == 'basic')
			{
				//To remove old category attribute values..
				$product_category_id = Product::whereRaw('id = ?', array($input_arr['id']))->pluck('product_category_id');
				if($product_category_id != $input_arr['my_category_id'])
				{
					$this->removeProductCategoryAttribute($input_arr['id']);
				}
				$data_arr = array('product_name' => $input_arr['product_name'],
		                            'product_description' => $input_arr['product_description'],
		                            'product_support_content' => $input_arr['product_support_content'],
		                            'meta_title' => $input_arr['meta_title'],
		                            'meta_keyword' => $input_arr['meta_keyword'],
		                            'meta_description' => $input_arr['meta_description'],
		                            'product_highlight_text' => $input_arr['product_highlight_text'],
		                            'demo_url' => $input_arr['demo_url'],
		                            'demo_details' => $input_arr['demo_details'],
		                            'product_tags' => $input_arr['product_tags'],
		                            'user_section_id' => $input_arr['user_section_id'],
		                            'product_category_id' => $input_arr['my_category_id'],
		                            'last_updated_date' => \DB::raw('NOW()'),
			                      );
			}
			elseif($tab == 'price')
			{
				$is_free_product = isset($input_arr['is_free_product'])? $input_arr['is_free_product']: 'No';
				$data_arr = array('is_free_product' => $is_free_product);

		         if($is_free_product == 'No')
				 {
				 	if($input_arr['product_discount_price'] > 0)
					{
						$from_date = str_replace('/', '-', $input_arr['product_discount_fromdate']);
						$from_date = date('Y-m-d', strtotime($from_date));

						$to_date = str_replace('/', '-', $input_arr['product_discount_todate']);
						$to_date = date("Y-m-d", strtotime($to_date));

						$data_arr['product_discount_fromdate'] =  $from_date;
					 	$data_arr['product_discount_todate'] =  $to_date;
					}
					$data_arr['last_updated_date'] = \DB::raw('NOW()');
					$data_arr['product_price_currency'] = \Config::get('webshoppack::site_default_currency');
				 	$data_arr['product_price'] = $input_arr['product_price'];
				 	$data_arr['product_price_usd'] = CUtil::convertBaseCurrencyToUSD($input_arr['product_price'], \Config::get('webshoppack::site_default_currency'));
					$data_arr['product_discount_price'] = $input_arr['product_discount_price'];
					$data_arr['product_discount_price_usd'] = CUtil::convertBaseCurrencyToUSD($input_arr['product_discount_price'], \Config::get('webshoppack::site_default_currency'));
		            //$data_arr['allow_to_offer'] = isset($input_arr['allow_to_offer'])? $input_arr['allow_to_offer']: 'No';
				 }
			}
			elseif($tab == 'attribute')
			{
				//To delete old attribute values..
				$this->removeProductCategoryAttribute($input_arr['id']);
				$attr_status = $this->addProductCategoryAttribute($input_arr);
				return array('status' => $attr_status, 'validate_tab_arr' => $this->validate_tab_arr);
			}
			elseif($tab == 'publish')
			{
				if($input_arr['product_notes'] != '')
				{
					$note_arr = array('product_id' => $input_arr['id'], 'comment' => $input_arr['product_notes']);
					$c_id = $this->addProductStatusComment($note_arr);
				}
				$data_arr['delivery_days'] = $input_arr['delivery_days'];
				$data_arr['last_updated_date'] = \DB::raw('NOW()');
				//To update status
				if($input_arr['edit_product'] != '')
				{
					if($input_arr['edit_product'] == 'publish')
					{
						$validate_tab_arr = $this->checkProductForPublish($input_arr['id']);
						if($validate_tab_arr['allow_to_publish'])
						{
							$this->hide_publish_tab_content = true;
							$this->alert_message = 'product_publish_success';
							$data_arr['product_status'] = 'Ok';

							//To update product activated date time.
							$date_activated = Product::whereRaw('id = ?', array($input_arr['id']))->pluck('date_activated');
							if($date_activated == '0000-00-00 00:00:00')
							{
								//To update prouduct activated date time.
								Product::whereRaw('id = ?', array($input_arr['id']))->update( array('date_activated' => \DB::raw('NOW()')));
							}
						}
						else
						{
							$this->validate_tab_arr = $validate_tab_arr['tab_arr'];
						}
					}
					else if($input_arr['edit_product'] == 'send_for_approval')
					{
						$validate_tab_arr = $this->checkProductForPublish($input_arr['id']);
						if($validate_tab_arr['allow_to_publish'])
						{
							$this->hide_publish_tab_content = true;
							$this->alert_message = 'product_send_for_approval_success';
							$data_arr['product_status'] = 'ToActivate';
						}
						else
						{
							$this->validate_tab_arr = $validate_tab_arr['tab_arr'];
						}
					}
					else if($input_arr['edit_product'] == 'update')
					{
						$this->hide_publish_tab_content = true;
						$this->alert_message = 'products_updated_success_msg';
					}
					else if($input_arr['edit_product'] == 'set_to_draft')
					{
						$this->hide_publish_tab_content = true;
						$this->alert_message = 'products_set_to_draft_success_msg';
						$data_arr['product_status'] = 'Draft';
					}
				}
			}
			elseif($tab == 'preview_files')
			{
				//No need any update for preview tab..
				return array('status' => true, 'validate_tab_arr' => $this->validate_tab_arr);

			}
			if(count($data_arr) > 0)
			{
				Product::whereRaw('id = ?', array($input_arr['id']))->update($data_arr);
				//To update product status
				if(isset($data_arr['product_status']) && $data_arr['product_status'] == 'Ok')
				{
					$this->updateUserTotalProducts($this->logged_user_id);
				}
				//Send mail alert to user for publish and submit for approval products...
				if(isset($data_arr['product_status']) && ($data_arr['product_status'] == 'Ok' || $data_arr['product_status'] == 'ToActivate'))
				{
					$this->sendProductMailToUserAndAdmin($input_arr['id']);
				}
				$final_success = (isset($this->hide_publish_tab_content) && $this->hide_publish_tab_content) ? true : false;
			    return array('status' => true, 'validate_tab_arr' => $this->validate_tab_arr, 'final_success' => $final_success);
			}
		}
		return $return_arr;
	}

	public function removeProductCategoryAttribute($p_id)
	{
		//To delete product attributes values
		$rtn_val = ProductAttributesValues::whereRaw("product_id = ?", array($p_id))->delete();
		//To delete product attributes options values
		$rtn_val = ProductAttributesOptionValues::whereRaw("product_id = ?", array($p_id))->delete();
	}

	public function getNewTabKey($current_tab, $p_id)
	{
		$new_tab_key = '';
		if($current_tab != '')
		{
			$tab_keys = array_keys($this->p_tab_arr);
			$new_tab_index = array_search($current_tab, $tab_keys);
			$new_tab_key =  isset($tab_keys[$new_tab_index +1 ])? $tab_keys[$new_tab_index +1 ] : '';
			//Check Attribute & download are available for this product
			if($new_tab_key == 'attribute' || $new_tab_key == 'download_files')
			{
				$p_details = Product::whereRaw('id = ?', array($p_id))->first()->toArray();
				if(count($p_details) > 0)
				{
					if($new_tab_key == 'attribute')
					{
						if(!$this->checkProductHasAttributeTab($p_details['product_category_id']))
						{
							return $this->getNewTabKey($new_tab_key, $p_id);
						}
					}
					else
					{
						if($p_details['is_downloadable_product'] == 'No')
						{
							return $this->getNewTabKey($new_tab_key, $p_id);
						}
					}
				}
			}
		}
		return $new_tab_key;
	}

	public function populateProductDefaultThumbImages($p_id)
	{
		$p_img_arr = ProductImage::where('product_id', '=', $p_id)
										->first();
		return $p_img_arr;
	}

	public function populateProductResources($p_id, $resource_type='', $is_downloadable = 'No')
	{
		$d_arr = ProductResource::where('product_id', '=', $p_id)->where('resource_type', '=', $resource_type)->where('is_downloadable', '=', $is_downloadable)
				 ->orderBy('display_order', 'ASC')
				 ->get(array('id', 'resource_type', 'filename', 'ext', 'title', 'is_downloadable', 'width', 'height', 't_width', 't_height', 'l_width', 'l_height'))
				 ->toArray();

	    $resources_arr = array();
		$download_filename = '';
		$download_url = '';
		foreach($d_arr AS $data)
		{
			if ($is_downloadable == 'Yes')
			{
	    		$download_filename = preg_replace('/[^0-9a-z\.\_\-]/i','', $data['title']);
				if (empty($download_filename))
				{
		    		$download_filename = md5($p_id);
		    	}
				$download_url = \URL::action('Agriya\Webshoppack\ProductAddController@getProductActions'). '?action=download_file&product_id=' . $p_id;
	    	}

			$product_preview_url = '';
			if($data['resource_type'] == 'Audio' || $data['resource_type'] == 'Video')
			{
				$product_preview_url = '';
			}

			$resources_arr[] = array(
				'resource_id'=>$data['id'],
				'resource_type'=>$data['resource_type'],
				'filename_thumb'=>$data['filename'] . 'T.' . $data['ext'],
				'filename_large'=>$data['filename'] . 'L.' . $data['ext'],
				'filename_original'=>$data['filename'] . '.' . $data['ext'],
				'download_filename'=>$download_filename . '.' . $data['ext'],
				'download_url'=> $download_url,
				'width'=>$data['width'],
				'height'=>$data['height'],
				't_width'=>$data['t_width'],
				't_height'=>$data['t_height'],
				'l_width'=>$data['l_width'],
				'l_height'=>$data['l_height'],
				'ext'=> $data['ext'],
				'title'=>$data['title'],
				'is_downloadable'=>$data['is_downloadable'],
				'product_preview_url'=> $product_preview_url
			);
		}
		return $resources_arr;
	}

	public static function getProductDefaultThumbImage($p_id, $image_size = "thumb", $p_image_info = array())
	{
		$image_exists = false;
		$image_details = array();
		$image_title = trans('webshoppack::product.no_image');
		$no_image = true;
		if(count($p_image_info) > 0 && $image_size == "thumb" && isset($p_image_info['thumbnail_img']) && $p_image_info['thumbnail_img'] != '' )
		{
			$image_exists = true;
			$image_details["thumbnail_img"] = $p_image_info->thumbnail_img;
			$image_details["thumbnail_ext"] = $p_image_info->thumbnail_ext;
			$image_details["thumbnail_width"] = $p_image_info->thumbnail_width;
			$image_details["thumbnail_height"] = $p_image_info->thumbnail_height;
			$image_details["thumbnail_title"] = $p_image_info->thumbnail_title;
			$image_details["image_folder"] = \Config::get("webshoppack::photos_folder");
		}

		if(count($p_image_info) > 0 && $image_size == "default" && isset($p_image_info['default_img']) && $p_image_info['default_img'] != '' )
		{
			$image_exists = true;
			$image_details["default_img"] = $p_image_info->default_img;
			$image_details["default_ext"] = $p_image_info->default_ext;
			$image_details["default_width"] = $p_image_info->default_width;
			$image_details["default_height"] = $p_image_info->default_height;
			$image_details["default_title"] = $p_image_info->default_title;
			$image_details["image_folder"] = \Config::get("webshoppack::photos_folder");
		}
		if(count($p_image_info) > 0 && $image_size == "indexsmall" && isset($p_image_info['default_img']) && $p_image_info['default_img'] != '' )
		{
			$image_exists = true;
			$image_details["thumbnail_img"] = $p_image_info->thumbnail_img;
			$image_details["thumbnail_ext"] = $p_image_info->thumbnail_ext;
			$image_details["thumbnail_width"] = $p_image_info->thumbnail_width;
			$image_details["thumbnail_height"] = $p_image_info->thumbnail_height;
			$image_details["thumbnail_title"] = $p_image_info->thumbnail_title;
			$image_details["image_folder"] = \Config::get("webshoppack::photos_folder");
		}

		$image_path = "";
		$image_url = "";
		$image_attr = "";
		if($image_exists)
		{
			$image_path = \URL::asset(\Config::get("webshoppack::photos_folder"))."/";
		}

		$cfg_user_img_large_width = \Config::get("webshoppack::photos_large_width");
		$cfg_user_img_large_height = \Config::get("webshoppack::photos_large_height");

		$cfg_user_img_thumb_width = \Config::get("webshoppack::photos_thumb_width");
		$cfg_user_img_thumb_height = \Config::get("webshoppack::photos_thumb_height");

		$cfg_user_img_indexsmall_width = \Config::get("webshoppack::photos_indexsmall_width");
		$cfg_user_img_indexsmall_height = \Config::get("webshoppack::photos_indexsmall_height");

		switch($image_size)
		{
			case 'default':
				$image_url = \URL::asset("packages/agriya/webshoppack/images/no_image").'/'.\Config::get("webshoppack::photos_large_no_image");
				$image_attr = CUtil::TPL_DISP_IMAGE($cfg_user_img_large_width, $cfg_user_img_large_height, $cfg_user_img_large_width, $cfg_user_img_large_height);

				if($image_exists)
				{
					$image_url =  $image_path . $image_details["default_img"]."L.".$image_details["default_ext"];
					$image_attr = CUtil::TPL_DISP_IMAGE($cfg_user_img_large_width, $cfg_user_img_large_height, $image_details["default_width"], $image_details["default_height"]);
					$image_title = $image_details["default_title"];
					$no_image = false;
				}
				break;

			case "thumb":

				$image_url = \URL::asset("packages/agriya/webshoppack/images/no_image").'/'.\Config::get("webshoppack::photos_thumb_no_image");

				$image_attr = CUtil::TPL_DISP_IMAGE($cfg_user_img_thumb_width, $cfg_user_img_thumb_height, $cfg_user_img_thumb_width, $cfg_user_img_thumb_height);

				if($image_exists)
				{
					$image_url =  $image_path . $image_details["thumbnail_img"]."T.".$image_details["thumbnail_ext"];
					//$image_attr = CUtil::TPL_DISP_IMAGE($cfg_user_img_thumb_width, $cfg_user_img_thumb_height, $image_details["thumbnail_width"], $image_details["thumbnail_height"]);
					$image_title = $image_details["thumbnail_title"];
					$no_image = false;
				}
				break;

			case "indexsmall":

				$image_url = \URL::asset("packages/agriya/webshoppack/images/no_image").'/'.\Config::get("webshoppack::photos_indexsmall_no_image");

				$image_attr = CUtil::TPL_DISP_IMAGE($cfg_user_img_indexsmall_width, $cfg_user_img_indexsmall_height, $cfg_user_img_indexsmall_width, $cfg_user_img_indexsmall_height);

				if($image_exists)
				{
					$image_url =  $image_path . $image_details["thumbnail_img"]."IS.".$image_details["thumbnail_ext"];
					$image_attr = CUtil::TPL_DISP_IMAGE($cfg_user_img_indexsmall_width, $cfg_user_img_indexsmall_height, $cfg_user_img_indexsmall_width, $cfg_user_img_indexsmall_height);
					$image_title = $image_details["thumbnail_title"];
					$no_image = false;
				}
				break;

			default:

				$image_url = \URL::asset("packages/agriya/webshoppack/images/no_image").'/product-thumb-170.jpg';
				$image_attr = CUtil::TPL_DISP_IMAGE(170, 150, 170, 150);

				if($image_exists)
				{
					$image_url =  $image_path . $image_details["thumbnail_img"]."T.".$image_details["thumbnail_ext"];
					$image_attr = CUtil::TPL_DISP_IMAGE(170, 150, $image_details["image_thumb_width"], $image_details["image_thumb_height"]);
					$image_title = $image_details["thumbnail_title"];
					$no_image = false;
				}
		}
		$image_details['image_url'] = $image_url;
		$image_details['image_attr'] = $image_attr;
		$image_details['title'] = $image_title;
		$image_details['no_image'] = $no_image;
		return $image_details;
	}

	public function saveProductImageTitle($p_id, $type, $title)
	{
		if (strcmp($type, 'thumb') == 0)
		{
			ProductImage::whereRaw('product_id = ?', array($p_id))->update(array('thumbnail_title' => $title));

		}
		else
		{
			ProductImage::whereRaw('product_id = ?', array($p_id))->update(array('default_title' => $title));
		}
        return true;
	}

	public function updateProductStatus($p_id, $product_status = 'Draft')
	{
	 	if(is_numeric($p_id) && $p_id > 0)
	 	{
			Product::whereRaw('id = ?', array($p_id))->update(array('product_status' => $product_status, 'last_updated_date' => \DB::raw('NOW()')));
		}
	}

	 public function setProductPreviewType($p_id)
	 {
		$this->product_media_type = Product::where('id', '=', $p_id)->pluck('product_preview_type');
	 }

	 public function  setAllowedUploadFormats($file_context = '')
	 {
		$allowed_formats = false;
		if ($this->product_media_type != '') {
			switch($this->product_media_type)
			{
				case 'image':

					if ($file_context == 'thumb')
					{
						$allowed_formats = implode(',', \Config::get("webshoppack::thumb_format_arr"));
					}
					elseif ($file_context == 'default')
					{
						$allowed_formats = implode(',', \Config::get("webshoppack::default_format_arr"));
					}
					elseif ($file_context == 'preview')
					{
						$allowed_formats = implode(',', \Config::get("webshoppack::preview_format_arr"));
					}

					break;
				case 'archive':
					$allowed_formats = implode(',', \Config::get("webshoppack::download_format_arr"));
					break;
			}
		}
		$this->allowed_upload_formats = $allowed_formats;
	 }

	public function  setMaxUploadSize($file_context = '')
	{
		$item_max_upload_size = 0;

		if ($this->product_media_type != '')
		{
			switch($this->product_media_type)
			{
				case 'image':
					if ($file_context == 'thumb')
					{
						$item_max_upload_size = \Config::get("webshoppack::thumb_max_size");
					}
					elseif ($file_context == 'default')
					{
						$item_max_upload_size = \Config::get("webshoppack::default_max_size");
					}
					elseif ($file_context == 'preview')
					{
						$item_max_upload_size = \Config::get("webshoppack::preview_max_size");
					}

					break;
				case 'archive':
					$item_max_upload_size = \Config::get("webshoppack::download_max_size");
					break;
			}
		}
		$this->product_max_upload_size = $item_max_upload_size;
	}

	public function insertResource($data)
	{
		$id = 0;
		if(count($data) > 0)
		{
		    $d_arr = array('product_id' => $data['product_id'],
		 		  		'resource_type' => $data['resource_type'],
						'filename' => $data['filename'],
						'ext' => $data['ext'],
						'title' => $data['title'],
						'width' => $data['width'],
						'height' => $data['height'],
						'l_width' => $data['l_width'],
						'l_height' => $data['l_height'],
						't_width' => $data['t_width'],
						't_height' => $data['t_height'],
						'server_url'=>$data['server_url'],
						'is_downloadable'=>$data['is_downloadable']
						);
		   $id = ProductResource::insertGetId($d_arr);
		}
		return $id;
    }

    public function uploadMediaFile($file_ctrl_name, $file_type,  &$file_info, $download_file = false)
	{
		if (!isset($_FILES[$file_ctrl_name])) return array('status'=>'error', 'error_message' => trans("webshoppack::product.products_select_file"));

		// default settings
		$file_original = '';
		$file_thumb = '';
		$file_large ='';
		$width = 0;
		$height = 0;
		$t_width = 0;
		$t_height = 0;
		$l_width = 0;
		$l_height = 0;
		$server_url = '';
		$is_downloadable = 'No';

		$file = \Input::file('uploadfile');
		$file_size = $file->getClientSize();
		if($file_size == 0)
		{
			return array('status'=>'error', 'error_message' => trans("webshoppack::product.common_err_tip_invalid_file_size"));
		}
		$upload_file_name = $file->getClientOriginalName();
		$ext_index = strrpos($upload_file_name, '.') + 1;
		$ext = substr($upload_file_name, $ext_index, strlen($upload_file_name));
		$title = substr($upload_file_name, 0, $ext_index - 1);
		$filename_no_ext = uniqid(); // generate filename
		//$file = $filename_no_ext . '.' . $ext;

		if (!($file_size  <= $this->product_max_upload_size * 1024 * 1024))// size in MB
		{
			return array('status'=>'error', 'error_message' => trans("webshoppack::product.common_err_tip_invalid_file_size"));
		}

		switch($file_type) {
			case 'image':
				$file_path = \Config::get("webshoppack::photos_folder");
				$server_url = \URL::asset($file_path);
				$file_original  = $filename_no_ext . '.' . $ext;
				$file_thumb = $filename_no_ext . 'T.' . $ext;
				$file_large = $filename_no_ext . 'L.' . $ext;
				$file_indexsmall = $filename_no_ext . 'IS.' . $ext;

				CUtil::chkAndCreateFolder($file_path);

				@chmod($file_original, 0777);
				@chmod($file_thumb, 0777);
				@chmod($file_large, 0777);
				@chmod($file_indexsmall, 0777);

				try{

					\Image::make($file->getRealPath())->save($file_path.$file_original);

					//Resize original image for large image
					\Image::make($file->getRealPath())
						->resize(\Config::get("webshoppack::photos_large_width"), \Config::get("webshoppack::photos_large_height"), true, false)
						->save($file_path.$file_large);

					 //Resize original image for thump image
					\Image::make($file->getRealPath())
						->resize(\Config::get("webshoppack::photos_thumb_width"), \Config::get("webshoppack::photos_thumb_height"), true, false)
						->save($file_path.$file_thumb);

					//Resize original image for small image for index page
					\Image::make($file->getRealPath())
						->resize(\Config::get("webshoppack::photos_indexsmall_width"), \Config::get("webshoppack::photos_indexsmall_height"), false, false)
						->save($file_path.$file_indexsmall);
				}
				catch(\Exception $e){
					return array('status'=>'error','error_message' => $e->getMessage());
				}

				list($width, $height) 		= getimagesize($file_path . $file_original);
				list($l_width, $l_height) 	= getimagesize($file_path . $file_large);
				list($t_width, $t_height) 	= getimagesize($file_path . $file_thumb);
				break;
			default:
				$file_type = ($file_type == 'archive') ? 'zip' : $file_type;
				$file_path = \Config::get("webshoppack::archive_folder");
				try
				{
					$file->move($file_path, $file_path . $filename_no_ext . '.' . $ext);
				}
				catch(\Exception $e)
				{
					return array('status'=>'error','error_message' => trans("webshoppack::product.products_file_upload_error"));
				}
				$is_downloadable = ($download_file) ? 'Yes' : 'No';
				break;
		}

		$file_info = array(
			'title'				=> $title,
			'filename_no_ext'	=> $filename_no_ext,
			'ext'				=> $ext,
			'file_original'		=> $file_original,
			'file_thumb'		=> $file_thumb,
			'file_large'		=> $file_large,
			'width'				=> $width,
			'height'			=> $height,
			't_width'			=> $t_width,
			't_height'			=> $t_height,
			'l_width'			=> $l_width,
			'l_height'			=> $l_height,
			'server_url'		=> $server_url,
			'is_downloadable'	=> $is_downloadable);

		 return array('status'=>'success');
	}

	public function updateItemProductImage($p_id, $title, $file_info)
	{
		$this->removeItemImageFile($p_id, 'thumb'); // removes actual file if already exists
		$data_arr = array('thumbnail_img' => $file_info['filename_no_ext'],
								'thumbnail_ext' =>$file_info['ext'],
								'thumbnail_width' => $file_info['t_width'],
								'thumbnail_height' => $file_info['t_height']);

		if(empty($title))
		{
			$data_arr = array('thumbnail_img' => $file_info['filename_no_ext'],
								'thumbnail_ext' =>$file_info['ext'],
								'thumbnail_width' => $file_info['t_width'],
								'thumbnail_height' => $file_info['t_height'],
								'thumbnail_title' => $file_info['title']);
		}
		ProductImage::whereRaw('product_id = ?', array($p_id))->update($data_arr);
	}

	public function removeItemImageFile($p_id, $type='')
	{
		$fields = 'default_img as file_name, default_ext as file_ext';
		if (strcmp($type, 'thumb') == 0)
		{
			$fields = 'thumbnail_img as file_name, thumbnail_ext as file_ext';
		}
		$condition = ' FROM product_image WHERE product_id = '.$p_id;
		$d_arr = \DB::select('SELECT '.$fields.$condition);
		if (count($d_arr) > 0)
		{
			foreach($d_arr AS $data)
			if ($data->file_name != '')
			{
				$file_path = \Config::get("webshoppack::photos_folder");

				if (file_exists($file_path.$data->file_name.'.'.$data->file_ext))
				{
					unlink($file_path.$data->file_name.'.'.$data->file_ext);
				}
				if (file_exists($file_path.$data->file_name.'T.'.$data->file_ext))
				{
					unlink($file_path.$data->file_name.'T.'.$data->file_ext);
				}
				if (file_exists($file_path.$data->file_name.'L.'.$data->file_ext))
				{
					unlink($file_path.$data->file_name.'L.'.$data->file_ext);
				}
				return true;
			}
		}
		return false;
	}

	public function updateProductDefaultImage($p_id, $title, $file_info)
	{
		$this->removeItemImageFile($p_id, 'default'); // removes actual file if already exists

		$data_arr = array('default_img' => $file_info['filename_no_ext'],
								'default_ext' => $file_info['ext'],
								'default_width' => $file_info['l_width'],
								'default_height' => $file_info['l_height'],
								'default_orig_img_width' => $file_info['width'],
								'default_orig_img_height' => $file_info['height']);

		if (empty($title))
		{
			$data_arr = array('default_img' => $file_info['filename_no_ext'],
								'default_ext' => $file_info['ext'],
								'default_width' => $file_info['l_width'],
								'default_height' => $file_info['l_height'],
								'default_title' => $file_info['title'],
								'default_orig_img_width' => $file_info['width'],
								'default_orig_img_height' => $file_info['height']);
		}
		ProductImage::whereRaw('product_id = ?', array($p_id))->update($data_arr);
	}

	public function removeProductThumbImage($p_id)
	{
		$this->removeItemImageFile($p_id, 'thumb'); // removes actual file
        $d_arr = array('thumbnail_img' => '' ,
		 		  		'thumbnail_ext' => '' ,
		 		  		'thumbnail_width' => 0,
		 		  		'thumbnail_height' => 0,
						'thumbnail_title' => '' );
		ProductImage::whereRaw('product_id = ?', array($p_id))->update($d_arr);
        return true;
	}

	public function getCategoryDropOptions()
	{
		$category_list = array('' => trans('webshoppack::common.select_option'));
		$c_data = ProductCategory::where('status', '=', 'active')
								->orderBy('category_left')
								->get( array('id', 'category_name', 'category_level'));

		foreach($c_data AS $row)
		{
			if($row['category_level'] != 0)
			{
				$category_list[$row['id']] = ($row['category_level']) ? str_repeat('&nbsp;', (self::MAX_CATEGORY_SPACING * ($row['category_level']))) . $row['category_name'] : $row['category_name'];
			}
		}
		return $category_list;
	}

	public function getSubCategoryIds($category_id)
	{
		$sub_category_ids_arr = array(0);
		$sub_cat_details = \DB::select('select node.id AS sub_category_id from product_category node, product_category parent where
				node.category_left BETWEEN parent.category_left AND parent.category_right AND parent.id = ? ORDER BY node.category_left', array($category_id));

		if(count($sub_cat_details) > 0)
		{
			$sub_category_ids_arr = array();
			foreach($sub_cat_details as $sub_cat)
			{
				$sub_category_ids_arr[] = $sub_cat->sub_category_id;
			}
		}
		return $sub_category_ids_arr;
	}

	public function removeProductDefaultImage($p_id)
	{
		$this->removeItemImageFile($p_id, 'default'); // removes actual file
        $d_arr = array('default_img' => '' ,
		 		  		'default_ext' => '' ,
		 		  		'default_width' => 0,
		 		  		'default_height' => 0,
		 		  		'default_orig_img_width' => 0,
		 		  		'default_orig_img_height' => 0,
						'default_title' => '' );
		ProductImage::whereRaw('product_id = ?', array($p_id))->update($d_arr);
        return true;
	}

	public function deleteProductResource($row_id)
	{
		# Get all attribute option ids related to the deleted attribute
		$d_arr = ProductResource::where('id', '=', $row_id)->get(array('filename', 'resource_type', 'ext'))->toArray();

	    foreach($d_arr AS $data)
	    {
			if($data['resource_type'] == 'Image')
			{
				$file_path = \Config::get("webshoppack::photos_folder");
				if (file_exists($file_path.$data['filename'].'.'.$data['ext']))
				{
					unlink($file_path.$data['filename'].'.'.$data['ext']);
				}
				if (file_exists($file_path.$data['filename'].'T.'.$data['ext']))
				{
					unlink($file_path.$data['filename'].'T.'.$data['ext']);
				}
				if (file_exists($file_path.$data['filename'].'L.'.$data['ext']))
				{
					unlink($file_path.$data['filename'].'L.'.$data['ext']);
				}
			}
			elseif($data['resource_type'] == 'Archive')
			{
				$file_path = \Config::get("webshoppack::archive_folder");
				if (file_exists($file_path.$data['filename'].'.'.$data['ext']))
				{
					unlink($file_path.$data['filename'].'.'.$data['ext']);
				}
			}
		}
		ProductResource::where('id', '=', $row_id)->delete();
		return $row_id;
	}

	public function downloadProductResouceFile($product_id = 0, $use_title = false)
	{
		$allowed_download = false;
		$q = \DB::select('SELECT filename, ext, resource_type, title, product_user_id, is_free_product FROM product_resource AS IRS, product AS MPI WHERE IRS.product_id = '.$product_id.' AND IRS.product_id = MPI.id AND is_downloadable = "Yes"');

		if(count($q) > 0)
		{
			//check if the logged in user has access
			if($q[0]->product_user_id == $this->logged_user_id || $q[0]->is_free_product == 'Yes')
			{
				$allowed_download = true;
				$filename = $q[0]->filename . '.'. $q[0]->ext;
				$media_type = (strtolower($q[0]->resource_type) == 'archive') ? 'zip' : strtolower($q[0]->resource_type);
				$path = \Config::get("webshoppack::archive_folder") ;

				if ($use_title && $q[0]->title != '')
				{
					$save_filename = preg_replace('/[^0-9a-z\.\_\-)]/i', '', $q[0]->title) . '.' . $q[0]->ext;
				}
				else
				{
					$save_filename = md5($product_id) . '.' . $q[0]->ext;
				}

				$pathToFile = public_path().'/'.$path.$filename;

				if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
				{
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="'.$save_filename.'"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header("Content-Transfer-Encoding: binary");
					header('Pragma: public');
					header("Content-Length: ".filesize($pathToFile));
				}
				else
				{
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="'.$save_filename.'"');
					header("Content-Transfer-Encoding: binary");
					header('Expires: 0');
					header('Pragma: no-cache');
					header("Content-Length: ".filesize($pathToFile));
				}

				ob_clean();
				flush();
				@readfile($pathToFile);
			}
		}
		if(!$allowed_download)
			die('Error: Unable to get the file!');
	}

	public function updateProductResourceImageTitle($resource_id, $title)
	{
		ProductResource::whereRaw('id = ?', array($resource_id))->update(array('title' => $title));
	    return true;
	}

	public function getProductNotes($p_id)
	{
		return ProductLog::whereRaw('product_id = ?', array($p_id))->orderBy('id', 'DESC')->get();
	}

	public function addProductStatusComment($input_arr)
	{
		$c_id = 0;
		if(count($input_arr) > 0 )
		{
			$user_type = 'User';
			if($this->logged_user_id > 0) {
				if(\Config::get("webshoppack::is_admin"))
				{
					$user_type = 'Admin';
				}
			}

			$data_arr = array('user_id' => $this->logged_user_id,
	                          'product_id' => $input_arr['product_id'],
	                          'added_by' => $user_type,
	                          'notes' => (isset($input_arr['comment']))? $input_arr['comment']: '',
	                          'date_added' => \DB::raw('NOW()'));
			$c_id = ProductLog::insertGetId($data_arr);
		}
		return $c_id;
	}

	public function checkProductForPublish($p_id)
	{
		$rtn_arr = array('allow_to_publish' => false, 'tab_arr' => array());
		$p_details = Product::whereRaw('id = ?', array($p_id))->first()->toArray();
		if(count($p_details) > 0)
		{
			$tab_arr = $this->validateTabList($p_id, $p_details);
			//Check manually for download tab..
			$available_tab_arr = array_filter($tab_arr, function ($val){ return (($val) ? true: false);});
			$allow_to_publish = (count($tab_arr) == count($available_tab_arr))? true : false;
			$rtn_arr = array('allow_to_publish' => $allow_to_publish, 'tab_arr' => $tab_arr);
		}
		return $rtn_arr;
	}

	public function validateTabList($p_id, $input_arr = array())
	{
		if($input_arr['product_discount_price'] > 0)
		{
			$input_arr['product_discount_fromdate'] = date('d/m/Y', strtotime($input_arr['product_discount_fromdate']));
			$input_arr['product_discount_todate'] = date('d/m/Y', strtotime($input_arr['product_discount_todate']));
		}

		 $tab_arr = array_map(function ($val){ return false;}, $this->p_tab_arr);
		 if(count($input_arr) > 0)
		 {
			$p_id = ($p_id == '')? 0 : $p_id;
			//check prodcut category has attributes..
			if(isset($input_arr['product_category_id'])) //No need to check for add product page
			{
				 $has_attr_tab = $this->checkProductHasAttributeTab($input_arr['product_category_id']);
				 if(!$has_attr_tab)
				 {
				 	unset($tab_arr['attribute']);
				 }
			}
			//Check download option are avalilable for this product
			if(isset($input_arr['is_downloadable_product']) && $input_arr['is_downloadable_product'] == 'No')
			{
				unset($tab_arr['download_files']);
			}
	 		foreach($tab_arr AS $key => $name)
		 	{
				if($key == 'download_files')
				{
					if($this->validateDownloadTab($p_id))
					{
						$tab_arr[$key] = true;
					}
					/*else
					{
						break;
					}*/
				}
				else
				{
					$temp_input_arr = $input_arr;
					if($key == 'attribute')
					{
						$temp_input_arr = $this->getProductCategoryAttributeValue($p_id, $input_arr['product_category_id']);
					}
					$validator_arr = $this->getproductValidation($temp_input_arr, $p_id, $key);
					$validator = \Validator::make($input_arr, $validator_arr['rules'], $validator_arr['messages']);
					if($validator->passes())
					{
						$tab_arr[$key] = true;
					}
					/*else
					{
						break;
					}*/
				}
			}
		 }
		 return $tab_arr;
	}

	public function getProductCategoryAttributeValue($p_id, $product_category_id)
	{
		$input_arr = array();
		$attr_arr = $this->getAttributesList($product_category_id);
		foreach($attr_arr AS $key => $val)
		{
			$id = $val['attribute_id'];
			$key = 'attribute_'.$id;
			if($val['validation_rules'] != '')
			{
				$attr_type = $val['attribute_question_type'];
				switch($attr_type)
				{
					case 'text':
					case 'textarea':
						$input_arr[$key] = ProductAttributesValues::whereRaw('product_id = ? AND attribute_id = ?', array($p_id, $id))->pluck('attribute_value');
						break;

					case 'select':
					case 'option': // radio button
					case 'multiselectlist':
					case 'check': // checkbox
						$option_val = ProductAttributesOptionValues::whereRaw('product_id = ? AND attribute_id = ?', array($p_id, $id))->get( array('attribute_options_id'));
						foreach($option_val AS $option)
						{
							$input_arr[$key][] = $option->attribute_options_id;
						}
						break;
				}
			}
		}
		return $input_arr;
	}

	public function getAttributesList($category_id, $p_id = 0)
	{
		$data_arr = array();
		if(is_numeric($category_id) && $category_id > 0)
		{
			//get all the category_id up in tree and the corresponding attribute ids..
			$category_ids = $this->getAllTopLevelCategoryIds($category_id);

			$q = ' SELECT MCA.attribute_id, attribute_question_type, validation_rules, default_value, MA.status , attribute_label ' .
						   ' FROM product_attributes AS MA LEFT JOIN ' .
						   ' product_category_attributes AS MCA ON MA.id = MCA.attribute_id '.
						   ' WHERE MCA.category_id IN ('.$category_ids.') '.
						   ' ORDER BY display_order, MA.id';
			$recs_arr = \DB::select($q);
			foreach($recs_arr AS $key => $val)
			{
				$dafault_value =  $val->default_value;
				//If product is avalilable, set the form field values by user entered data
				if($p_id > 0)
				{
					$dafault_value = $this->getAttributeValue($p_id, $val->attribute_id, $val->attribute_question_type, $dafault_value);
				}

				$data_arr[$val->attribute_id] = array('attribute_id' => $val->attribute_id,
													  'attribute_question_type' => $val->attribute_question_type,
													  'validation_rules' => $val->validation_rules,
													  'default_value' => $dafault_value,
													  'status' => $val->status,
													  'attribute_label' => $val->attribute_label
												);
			}
		}
		return $data_arr;
	}

	public function getAttributeValue($p_id, $attr_id, $attr_type, $dafault_value)
	{
		switch($attr_type)
		{
			case 'text':
			case 'textarea':
				$count = ProductAttributesValues::where('attribute_id', '=', $attr_id)->where('product_id', '=', $p_id)->count();
				if($count > 0)
				{
					return ProductAttributesValues::where('attribute_id', '=', $attr_id)->where('product_id', '=', $p_id)->pluck('attribute_value');
				}
				break;

			case 'select':
			case 'option': // radio button
			case 'multiselectlist':
			case 'check': // checkbox
				$count = ProductAttributesOptionValues::where('attribute_id', '=', $attr_id)->where('product_id', '=', $p_id)->count();
				if($count > 0)
				{
					$rtn_arr = array();
					$t_arr = ProductAttributesOptionValues::where('attribute_id', '=', $attr_id)->where('product_id', '=', $p_id)->get(array('attribute_options_id'))
								->toArray();
					foreach($t_arr AS $arr)
					{
						$rtn_arr[] = $arr['attribute_options_id'];
					}
					return $rtn_arr;
				}
				break;
		}
		return $dafault_value;

	}

	public function getProductViewURL($p_id, $p_details = array())
	{
		$url_slug = '';
		if(isset($p_details['product_code']) && isset($p_details['url_slug']))
		{
			$url_slug = $p_details['product_code']. '-'.$p_details['url_slug'];
		}
		else
		{
			$p_details = Product::where('id', $p_id)->first(array('product_code', 'url_slug'));
			if(count($p_details) > 0)
			{
				$url_slug = $p_details['product_code']. '-'.$p_details['url_slug'];
			}
		}
		if($url_slug != '')
		{
			$view_url = \URL::to(\Config::get('webshoppack::uri').'/view/'.$url_slug);
			return $view_url;
		}
		return '';
	}
	public function getAdminProductViewURL($p_id, $p_details = array())
	{
		$url_slug = '';
		if(isset($p_details['product_code']) && isset($p_details['url_slug']))
		{
			$url_slug = $p_details['product_code']. '-'.$p_details['url_slug'];
		}
		else
		{
			$p_details = Product::where('id', $p_id)->first(array('product_code', 'url_slug'));
			if(count($p_details) > 0)
			{
				$url_slug = $p_details['product_code']. '-'.$p_details['url_slug'];
			}
		}
		if($url_slug != '')
		{
			$view_url = \URL::to(\Config::get('webshoppack::admin_uri').'/view/'.$url_slug);
			return $view_url;
		}
		return '';
	}

	public function checkIsShopNameExist()
	{
		$shop_name = ShopDetails::where('user_id', '=', $this->logged_user_id)->pluck('shop_name');
		return ($shop_name == '')? false : true;
	}

	public function getAttributeOptions($attribute_id)
	{
		$d_arr = ProductAttributeOptions::where('attribute_id', '=', $attribute_id)	->orderBy('id', 'ASC')->get(array('id', 'option_label'))->toArray();
		$data = array();
		foreach($d_arr AS $val)
		{
			$data[$val['id']] = $val['option_label'];
		}
		return $data;
	}

	public function addProductCategoryAttribute($input_arr)
	{
		if(isset($input_arr['product_category_id']) && is_numeric($input_arr['product_category_id']))
		{
			$attr_arr = $this->getAttributesList($input_arr['product_category_id']);
			foreach($input_arr AS $key => $val)
			{
				if(starts_with($key, 'attribute_'))
				{
					$name_arr = explode('_', $key);
					if(count($name_arr) == 2)
					{
						$id = $name_arr[1];
						if(isset($attr_arr[$id]) && $attr_arr[$id]['attribute_question_type'] != '')
						{
							$attr_type = $attr_arr[$id]['attribute_question_type'];
							switch($attr_type)
							{
								case 'text':
								case 'textarea':
									$this->insertProductAttribute($input_arr['id'], $id, $input_arr['attribute_'.$id]);
									break;

								case 'select':
								case 'option': // radio button
								case 'multiselectlist':
								case 'check': // checkbox
									$this->insertAttributeOption($input_arr, $id);
									break;
							}
						}
					}
				}
			}
			$this->updateLastUpdatedDate($input_arr['id']);
			return true;
		}
		return false;;
	}

	public function insertProductAttribute($p_id, $attribute_id, $attribute_value)
	{
		$data_arr = array('product_id' => $p_id,
						  'attribute_id' => $attribute_id,
						  'attribute_value' => $attribute_value
						);
		$a_id = ProductAttributesValues::insertGetId($data_arr);
	}

	public function insertAttributeOption($input_arr, $attribute_id)
	{
		if(isset($input_arr['attribute_'.$attribute_id]))
		{
			if(is_array($input_arr['attribute_'.$attribute_id]))
			{
				foreach($input_arr['attribute_'.$attribute_id] AS $attr_key => $attr_val)
				{
					$data_arr = array('product_id' => $input_arr['id'],
								'attribute_id' => $attribute_id,
								'attribute_options_id' => $attr_val);
					$a_id = ProductAttributesOptionValues::insertGetId($data_arr);
				}
			}
			else
			{
				$data_arr = array('product_id' => $input_arr['id'],
									'attribute_id' => $attribute_id,
									'attribute_options_id' => $input_arr['attribute_'.$attribute_id]);
				$a_id = ProductAttributesOptionValues::insertGetId($data_arr);
			}
		}
	}

	public function updateLastUpdatedDate($p_id)
	 {
	 	if(is_numeric($p_id) && $p_id > 0)
	 	{
			Product::whereRaw('id = ?', array($p_id))->update(array('last_updated_date' => \DB::raw('NOW()')));
		}
	 }

	public function fetchShopItems($shop_user_id, $current_p_id = 0, $limit = 5)
	{
		$items_arr = array();
		$p_details = Product::whereRaw('product_user_id = ? AND product_status = ? AND id != ?', array($shop_user_id, 'Ok', $current_p_id))
								->orderByRaw('RAND()')
								->take($limit)
								->Select(\DB::raw("product.id, product.product_status, product.total_downloads, product.url_slug, product.product_user_id, product.product_sold, product.product_added_date,
									   product.product_category_id, product.product_tags, product.is_free_product, product.total_views, product.product_discount_price, product.product_discount_fromdate,
									   product.product_discount_todate, product.product_price, product.product_name, product.product_description, product.product_highlight_text,
									   product.date_activated, NOW() as date_current, IF( ( DATE( NOW() ) BETWEEN product.product_discount_fromdate AND product.product_discount_todate), 1, 0 ) AS have_discount,
									   product.product_price_currency, product.product_price_usd, product.product_discount_price_usd"))
								->get( array('id', 'product_code', 'product_name', 'url_slug'));
		return $p_details;
	}

	public function getProductShopURL($id, $shop_details = array())
	{
		$url_slug = '';
		if(isset($shop_details['url_slug']))
		{
			$url_slug = $shop_details['url_slug'];
		}
		else
		{
			$s_details = ShopDetails::where('id', $id)->first(array('url_slug'));
			if(count($s_details) > 0)
			{
				$url_slug = $s_details['url_slug'];
			}
		}
		if($url_slug != '')
		{
			$view_url = \URL::to(\Config::get('webshoppack::shop_uri').'/'.$url_slug);
			return $view_url;
		}
		return '';
	}


	public function getProductList($id)
	{
		$product_list =	Product::where('product_user_id', '=', $id)
						->paginate(\Config::get('webshoppack::paginate'));
        return $product_list;
	}

	public function getProductStatusArr()
	{
		$status_arr = array( 'All' => \Lang::get('webshoppack::product.refine.all'),
							 'Draft' => \Lang::get('webshoppack::product.refine.draft_label'),
							 'Ok' => \Lang::get('webshoppack::product.refine.active'),
							 'ToActivate' => \Lang::get('webshoppack::product.refine.rejected_label'),
							 'NotApproved' => \Lang::get('webshoppack::product.refine.pending_approval_label')
									);
		return $status_arr;
	}

	public function setSearchFields($input)
	{
		$this->srch_arr['search_product_code'] =(isset($input['search_product_code']) && $input['search_product_code'] != '') ? $input['search_product_code'] : "";
		$this->srch_arr['search_product_name'] =(isset($input['search_product_name']) && $input['search_product_name'] != '') ? $input['search_product_name'] : "";
		$this->srch_arr['search_product_category']= (isset($input['search_product_category']) && $input['search_product_category'] != '') ? $input['search_product_category'] : "";
		$this->srch_arr['search_product_status']= (isset($input['search_product_status']) && $input['search_product_status'] != '') ? $input['search_product_status'] : "All";
	}

	public function getSearchValue($key)
	{
		return (isset($this->srch_arr[$key])) ? $this->srch_arr[$key] : '';
	}

	public function buildMyProductQuery()
	{
		$user = \Config::get('webshoppack::logged_user_id');
		$user_id = $user();
		$this->qry = Product::select('Product.id', 'Product.product_code', 'Product.product_name', 'Product.product_price', 'Product.product_price_currency', 'Product.product_sold', 'Product.product_discount_price',
									 'Product.product_discount_fromdate', 'Product.product_discount_todate', 'Product.is_free_product', 'Product.product_status')->where('Product.product_user_id', $user_id);
		$this->qry->Where('Product.product_status', '!=', 'Deleted');

		if($this->getSearchValue('search_product_code'))
		{
			$this->qry->Where('Product.product_code', $this->getSearchValue('search_product_code'));
		}

		if($this->getSearchValue('search_product_name') != '')
		{
			$this->qry->Where('Product.product_name', 'LIKE', '%'.addslashes($this->getSearchValue('search_product_name')).'%');
		}
		if($this->getSearchValue('search_product_category') > 0)
		{
			$cat_id_arr = $this->getSubCategoryIds($this->getSearchValue('search_product_category'));
			$this->qry->whereIn('Product.product_category_id', $cat_id_arr);
		}

		if($this->getSearchValue('search_product_status') != '' && $this->getSearchValue('search_product_status') != 'All')
		{
			$this->qry->where('Product.product_status', '=', $this->getSearchValue('search_product_status'));
		}

		$this->qry->orderBy('Product.id', 'DESC');
		return $this->qry;
	}

	public function deleteProduct($p_id, $p_details)
	{
		if(count($p_details) == 0)
		{
			$p_details = Product::whereRaw('id = ?', array($p_id))->first();
		}
		//To update product status to deleted
		$affected_rows = Product::where('id', '=', $p_id)->update( array('product_status' => 'Deleted'));
		return true;
	}

	public function changeFeaturedStatus($p_id, $p_details, $status)
	{
		if(count($p_details) == 0)
		{
			$p_details = Product::whereRaw('id = ?', array($p_id))->first();
		}
		$affected_rows = Product::where('id', '=', $p_id)->update( array('is_user_featured_product' => $status));
		if($affected_rows)
		{
			return true;
		}
		return false;
	}

	public function getBaseAmountToDisplay($price, $currency, $return_as_arr = false)
	{
		$currency_symbol = "USD";
		$currency_symbol_font = "$";

		$currency_details = $this->chkIsValidCurrency($currency);
		if(count($currency_details) > 0)
		{
			$currency_symbol = $currency_details["currency_code"];
			$currency_symbol_font = $currency_details["currency_symbol"];
			if($currency_symbol == "INR")
				$currency_symbol_font = "<em class=\"clsWebRupe\">".$currency_details["currency_symbol"]."</em>";
		}
		$formatted_amt = "";
		$formatted_amt = number_format ($price, 2, '.','');
		$formatted_amt = str_replace(".00", "", $formatted_amt);
		$formatted_amt = str_replace("Rs.", "", $formatted_amt);

		if($return_as_arr)
			return compact('currency_symbol','formatted_amt');
		else
			return "<small>".$currency_symbol. '</small> <strong>' . $formatted_amt.'</strong>';
	}

	public function chkIsValidCurrency($currency_code)
	{
		$details = array();
		$selected_currency_code = CurrencyExchangeRate::whereRaw('currency_code= ? AND status = "Active" AND display_currency = "Yes" ', array($currency_code))->first();
		if(count($selected_currency_code))
		{
			$details['country'] = $selected_currency_code['country'];
			$details['currency_code'] = $selected_currency_code['currency_code'];
			$details['exchange_rate'] = $selected_currency_code['exchange_rate'];
			$details['currency_symbol'] = $selected_currency_code['currency_symbol'];
		}
		return $details;
	}

	public function getTotalProduct($shop_user_id)
	{
		return Product::whereRaw('product_user_id = ? AND product_status = ?', array($shop_user_id, 'Ok'))->count();
	}

	public function formatProductPrice($product_details)
	{
		# Return values
		$price_details['disp_price'] = $price_details['disp_label'] = $price_details['disp_link'] = $price_details['disp_discount'] = false;

		# Assigned default values from the input
		$is_free_product = isset($product_details['is_free_product']) ? $product_details['is_free_product'] : 'No';
		$have_discount = isset($product_details['have_discount']) ? $product_details['have_discount'] : 0;
		$product_discount_price = isset($product_details['product_discount_price']) ? $product_details['product_discount_price'] : 0.00;
		$product_price = isset($product_details['product_price']) ? $product_details['product_price'] : 0.00;
		$product_discount_fromdate = isset($product_details['product_discount_fromdate']) ? $product_details['product_discount_fromdate'] : '';
		$product_discount_todate = isset($product_details['product_discount_todate']) ? $product_details['product_discount_todate'] : '';

		# If not checked the discount option in query & discount from & to dates passed then checked the discount
		if(!isset($product_details['have_discount']) && $product_discount_fromdate && $product_discount_todate)
		{
			$discount_from_time = strtotime($product_discount_fromdate);
			$discount_end_time = strtotime($product_discount_todate);
			$curr_time = strtotime(date('Y-m-d'));
			if($discount_end_date >= $curr_date && $discount_from_date <= $curr_date)
			{
				$have_discount = 1;
			}
		}

		# Checked discount or not
		if($is_free_product == 'No' && $have_discount && $product_discount_price > 0)
		{
			$price_details['disp_discount'] = true;
		}

		# Set price details
		if($product_price)
		{
			$price_details['disp_price'] = true;
		}
		return $price_details;
	}

	public function checkIsShopPaypalUpdated()
	{
		$shop_paypal_id = UsersShopDetails::where('user_id', '=', $this->logged_user_id)->pluck('paypal_id');
		return ($shop_paypal_id == '')? false : true;
	}

	public function updateUserTotalProducts($user_id)
	{
		$p_count = $this->getTotalProduct($user_id);
		UsersShopDetails::where('user_id', '=', $user_id)->update( array('total_products' => $p_count));
	}

	public function sendProductMailToUserAndAdmin($p_id, $user_notes = '')
	{
		$product_details = Product::where('id', $p_id)->first();
		$user_details = CUtil::getUserDetails($product_details->product_user_id);
		$product_code = $product_details->product_code;
		$url_slug = $product_details->url_slug;
		$view_url = $this->getProductViewURL($p_id, $product_details);
		$subject = trans('webshoppack::product.product_created_published');
		if($product_details['product_status'] == 'ToActivate')
		{
			$subject = trans('webshoppack::product.product_created_to_activate');
		}
		elseif($product_details['product_status'] == 'NotApproved')
		{
			$subject = trans('webshoppack::product.product_created_disapprove');
		}

		$data = array(
			'product_code'	=> $product_details['product_code'],
			'product_name'  		=> $product_details['product_name'],
			'url_slug'  		=> $product_details['url_slug'],
			'product_description' => CUtil::wordWrap($product_details['product_description'], 300),
			'product_user_id' => $product_details['product_user_id'],
			'is_free_product'	  => $product_details['is_free_product'],
			'product_status'	  => $product_details['product_status'],
			'product_status_lang'	  => $this->getProductStatusLang($product_details['product_status']),
			'product_tags'	  => $product_details['product_tags'],
			'display_name'	 => $user_details['display_name'],
			'user_email'	 => $user_details['email'],
			'view_url'		=> $view_url,
			'subject'		=> $subject,
			'user_notes' => $user_notes
		);

		//Mail to User
		\Mail::send('webshoppack::emails.productCreated', $data, function($m) use ($data) {
				$m->to($data['user_email']);
				$m->subject($data['subject']);
			});

		$this->sendNotificationMailForAdmin($p_id, compact('product_details', 'user_details'));
	}

	public function sendNotificationMailForAdmin($p_id, $arr)
	{
		$arr['product_details'] = isset($arr['product_details']) ?  $arr['product_details'] :
															 Product::where('id', $p_id)->first();
		$arr['user_details'] = isset($arr['user_details'])? $arr['user_details'] :
															 CUtil::getUserDetails($arr['product_details']->product_user_id);

		$view_url = $this->getProductViewURL($p_id, $arr['product_details']);
		$arr['product_details']['view_url'] = $view_url;
		$arr['product_details']['product_status_lang'] = $this->getProductStatusLang($arr['product_details']['product_status']);
		$arr['product_details']['product_notes'] = $this->getUserLastProductNote($p_id);

		$category_list = $this->retriveSingleCategoryPath($arr['product_details']['product_category_id']);
		$category_arr = array();
		foreach($category_list AS $cat)
		{
			$category_arr[] = $cat->category_name;
		}
		$category_arr = array_slice($category_arr, 1); //To remove root category
		$arr['product_details']['category'] = implode(' / ', $category_arr);
		$arr['product_details']['product_price'] = $arr['product_details']['product_price'];
		$arr['product_details']['product_discount_price'] = $arr['product_details']['product_discount_price'];
		$arr['product_details']['product_price_currency'] = $arr['product_details']['product_price_currency'];

		$subject = trans('webshoppack::product.product_created_published_admin');
		if($arr['product_details']['product_status'] == 'ToActivate')
		{
			$subject = trans('webshoppack::product.product_created_to_activate_admin');
		}
		elseif($arr['product_details']['product_status'] == 'NotApproved')
		{
			$subject = trans('webshoppack::product.product_created_disapprove_admin');
		}

		$arr['subject'] = $subject;
		$arr['admin_email'] = \Config::get('webshoppack::admin_email');

		\Mail::send('webshoppack::emails.productCreatedAdmin', $arr, function($m) use ($arr) {
			$m->to($arr['admin_email']);
			$m->subject($arr['subject']);
		});

	}

	public function retriveSingleCategoryPath($category_id)
	{
		$q = \DB::select('SELECT parent.category_name FROM product_category AS node, product_category AS parent WHERE node.category_left BETWEEN parent.category_left AND parent.category_right AND node.id = ? ORDER BY node.category_left;', array($category_id));
		return $q;
	}

	public function getProductStatusLang($p_status)
	{
		$status = $p_status;
		if($p_status == 'Ok')
		{
			$status = trans("webshoppack::product.status_active");
		}
		elseif($p_status == 'ToActivate')
		{
			$status = trans("webshoppack::product.status_to_activate");
		}
		elseif($p_status == 'NotApproved')
		{
			$status = trans("webshoppack::product.status_in_not_approved");
		}
		return $status;
	}

	public function getUserLastProductNote($p_id)
	{
		return ProductLog::whereRaw('product_id = ? AND user_id = ?', array($p_id, $this->logged_user_id))->orderBy('id', 'DESC')->pluck('notes');
	}

	public function updateProductResourceImageDisplayOrder($resourcednd)
	{
		foreach($resourcednd as $display_order=>$resource_id_str)
		{
			$temp = explode("_", $resource_id_str);
			$resource_id = (isset($temp[1]) && $temp[1]) ? (int) $temp[1] : false;

			if($resource_id)
			{
				ProductResource::whereRaw('id = ?', array($resource_id))->update(array('display_order' => $display_order));
			}
		}
	}

	public function populateOptionsArray()
	{
		$populateOptionsArray = array();
		$list_paging_sort_by = \Config::get("webshoppack::list_paging_sort_by");
		$inc = 0;
		foreach ($list_paging_sort_by as $key => $filter)
		{
			$populateOptionsArray[$inc]['href'] = \Request::url().'?orderby_field='.$filter;
			$inner_txt = (trans('webshoppack::product.product_listing_'.$key) != '') ? trans('webshoppack::product.product_listing_'.$key) : $key;
			$populateOptionsArray[$inc]['innervalue'] = $filter;
			$populateOptionsArray[$inc]['innertext'] = $inner_txt;
			$inc++;
		}
		return 	$populateOptionsArray;
	}

	public function setProductOrderBy($orderby_field)
	{
		if($orderby_field != "")
		{
			$this->order_by = $orderby_field;
		}
		else
		{
			$this->order_by = "id";
		}
	}

	public function remExcludeValuesFromSearchTags($searchheader_val)
	{
		$text_to_search = $searchheader_val;
		$symbol_arr = array(',', '.', '?', '*');
		$text_to_search = str_replace($symbol_arr, ' ', $text_to_search);
		//echo $text_to_search;
		$search_arr = explode(" ", $text_to_search);
		$search_arr = CUtil::arraytolower(array_filter($search_arr));
		//Fetch the allowed tags from csv file
		//$include_tagslist_arr = CUtil::arraytolower($this->fetchIncludeTagsFromCsv());
		//Checked the csv values matched the search values

		//$matched_array_values = array_intersect($include_tagslist_arr, $search_arr);
		$search_arr = array_filter($search_arr, 'Agriya\Webshoppack\CUtil::remminLength');
		//Merge the matched csv values into the array
		//$merged_arr = array_merge($search_arr, $matched_array_values);
		//$search_arr = array_unique(CUtil::arraytolower($merged_arr));
		$result_arr = $stop_words_arr = array();
		if(count($search_arr))
		{
			$stop_words_arr = $this->fetchExcludedTags($search_arr);

			if(!empty($stop_words_arr))
			{
				foreach($search_arr as $word)
				{
					if(!in_array($word, $stop_words_arr))
					{
						$word = strtolower($word);
						$result_arr[] = $word;
					}
				}
			}
			else
				$result_arr = array_values($search_arr);
		}
		return $result_arr;
	}

	public function fetchExcludedTags($search_arr)
	{
		$stop_words_arr = array();
		$tags = "";
		foreach($search_arr as $tag)
		{
			$tags .= "'".addslashes($tag)."'".',';
		}
		$tags = substr($tags, 0, strrpos($tags, ','));
		$tags_info = ApiExcludeTags::Select('tags')->whereRaw(\DB::raw('tags IN ('. $tags .')'))->get();
		if(count($tags_info) > 0)
		{
			$stop_words_arr[] = $tags_info->tags;
		}
	}

	/*public function fetchIncludeTagsFromCsv()
	{
		$row = 1;
		$include_tags_list = array();

		$destinationPath = \Config::get("webshoppack::tags_csv_file");
		$tagscsv_path = \public_path().'/'.$destinationPath;

		if (($handle = fopen($tagscsv_path, "r")) !== FALSE)
		{
		    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)
			{
		        $num = count($data);
		        $row++;
		        for ($c=0; $c < $num; $c++)
				{
		            $include_tags_list[] = $data[$c];
		        }
		    }
		    fclose($handle);
		}
		return $include_tags_list;
	}*/
}