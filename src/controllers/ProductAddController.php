<?php namespace Agriya\Webshoppack;

class ProductAddController extends \BaseController {

	function __construct()
	{
        $this->productService = new ProductService();
    }

	public function getIndex()
	{
		if(!$this->productService->checkIsShopNameExist() || !$this->productService->checkIsShopPaypalUpdated())
    	{
			return \Redirect::to(\Config::get('webshoppack::shop_uri').'/users/shop-details');
		}

		$d_arr = $category_main_arr = $category_sub_arr = array();

		$user = \Config::get('webshoppack::logged_user_id');
		$logged_user_id = $user();

		$title = trans('webshoppack::product.add_product_title');
		$error_msg = '';
		$category_id = (\Input::get('my_category_id') == '')? \Input::old('my_category_id', 1): \Input::get('my_category_id', 1);

		//To add/edit product details
		$p_id = (\Input::get('id') == '')? \Input::old('id'): \Input::get('id');
		$tab = (\Input::get('p') == '')? \Input::old('p', 'basic'): \Input::get('p', 'basic');
		$p_url = \URL::action('Agriya\Webshoppack\ProductAddController@postAdd');
		$action = 'add';
		$p_details = new Product();
		if($p_id != '')
		{
			//To validate product id
			if(is_numeric($p_id))
			{
				$p_details = Product::whereRaw('id = ? AND product_user_id = ?', array($p_id, $logged_user_id))->first();
				if(count($p_details) > 0)
				{
					$p_details = $p_details->toArray();
					$p_url = \URL::action('Agriya\Webshoppack\ProductAddController@postEdit');
					$category_id = $p_details['product_category_id'];
					$action = 'edit';
				}
				else
				{
					$p_id = '';
					$error_msg = trans('webshoppack::product.invalid_product_id');
				}
			}
			else
			{
				$p_id = '';
				$error_msg = trans('webshoppack::product.invalid_product_id');
			}
		}

		//Render user alert mesage
		if($error_msg != '')
		{
			Session::put('error_message', $error_msg);
		}

		//$this->header->setMetaTitle($title);
		if($tab == 'basic')
		{
			$section_arr = $this->productService->getProductSectionDropList($logged_user_id);

			//To get category list
			$category_main_arr = $this->productService->getCategoryListArr();
			$cat_list = $this->productService->getAllTopLevelCategoryIds($category_id);
			$top_cat_list_arr = explode(',', $cat_list);
			$top_cat_count = count($top_cat_list_arr);
			if($top_cat_count > 1)
			{

				foreach($top_cat_list_arr AS $sel_key => $top_cat_id)
				{
					$category_sub_arr[$top_cat_id] = $this->productService->getSubCategoryList($top_cat_id);
				}
			}
			$d_arr['my_selected_categories'] = $cat_list;
			$d_arr['top_cat_list_arr'] = $top_cat_list_arr;
			$d_arr['my_category_id'] = $category_id;
			$d_arr['top_cat_count'] = $top_cat_count;
			$d_arr['root_category_id'] = $this->productService->root_category_id;
		}
		elseif($tab == 'attribute')
		{
			$d_arr['attr_arr'] = $this->productService->getAttributesList($category_id, $p_id);
		}
		elseif($tab == 'preview_files')
		{
			$d_arr['p_img_arr'] = $this->productService->populateProductDefaultThumbImages($p_id);
			$d_arr['thumb_no_image'] = CUtil::DISP_IMAGE(145, 145, \Config::get("webshoppack::product.photos_thumb_width"), \Config::get("webshoppack::product.photos_thumb_height"), true);
			$d_arr['default_no_image'] = CUtil::DISP_IMAGE(578, 385, \Config::get("webshoppack::product.photos_large_width"), \Config::get("webshoppack::product.photos_large_height"), true);
			$d_arr['resources_arr'] = $this->productService->populateProductResources($p_id, 'Image');
		}
		elseif($tab == 'download_files')
		{
			$d_arr['resources_arr'] = $this->productService->populateProductResources($p_id, 'Archive', 'Yes');
		}
		elseif($tab == 'publish')
		{
			$d_arr['product_notes'] = $this->productService->getProductNotes($p_id);
		}
		$d_arr['p'] = $tab;
		$d_arr['tab_list'] = $this->productService->getTabList($p_id, $p_details, $action);
		if((!isset($d_arr['tab_list'][$tab])) || (isset($d_arr['tab_list'][$tab]) && !$d_arr['tab_list'][$tab]))
		{
			return \Redirect::to(\Config::get('webshoppack::uri').'/add')->with('error_message', trans('webshoppack::common.invalid_access'));
		}
		$service_obj = $this->productService;
		return \View::make(\Config::get('webshoppack::product_add'), compact('d_arr', 'section_arr', 'p_details', 'p_id', 'p_url', 'category_main_arr', 'category_sub_arr', 'service_obj', 'action', 'category_id'));
	}

	public function postAdd()
	{
		if(\Input::has('add_product'))
		{
			$input_arr = \Input::All();
			$input_arr['product_preview_type'] = 'image'; //Make default preview type is image now, We will get preview type from user in future..
			$validator_arr = $this->productService->getproductValidation($input_arr);
			$validator = \Validator::make($input_arr, $validator_arr['rules'], $validator_arr['messages']);
			if($validator->passes())
			{
				$add_product = $this->productService->addProduct($input_arr);
				if($add_product > 0)
				{
					//To redirect to next tab..
					return \Redirect::to(\Config::get('webshoppack::uri').'/add?id='.$add_product.'&p=price');
				}
			}
			else
			{
				return \Redirect::to(\Config::get('webshoppack::uri').'/add')->with('error_message', trans('webshoppack::common.correct_errors'))->withInput()->withErrors($validator);
			}
		}
		return \Redirect::to(\Config::get('webshoppack::uri').'/add');
	}

	public function postEdit()
	{
		if(\Input::has('edit_product'))
		{
			$input_arr = \Input::All();
			//Do the manual validation for download file
			if(isset($input_arr['p']) && $input_arr['p'] == 'download_files')
			{
				if($this->productService->validateDownloadTab($input_arr['id']))
				{
					$new_tab_key =  $this->productService->getNewTabKey($input_arr['p'], $input_arr['id']);
					if($new_tab_key == '')
					{
						//If all the completed, then redirect to product list page
						$msg = (empty($this->productService->alert_message))? '' : trans('webshoppack::product.'.$this->productService->alert_message);
						return \Redirect::to(\Config::get('webshoppack::uri').'/add?id='.$input_arr['id'].'&p=publish')->with('success_message', $msg);
					}
					else
					{
						//To redirect to next tab..
						return \Redirect::to(\Config::get('webshoppack::uri').'/add?id='.$input_arr['id'].'&p='.$new_tab_key);
					}
				}
				else
				{
					return \Redirect::to(\Config::get('webshoppack::uri').'/add?id='.$input_arr['id'].'&p=download_files')->with('error_message', trans('webshoppack::common.correct_errors'));
				}
			}
			else
			{
				$validator_arr = $this->productService->getproductValidation($input_arr, $input_arr['id'], $input_arr['p']);
				$validator = \Validator::make($input_arr, $validator_arr['rules'], $validator_arr['messages']);
				if($validator->passes())
				{
					$update_product_arr = $this->productService->updateProduct($input_arr, $input_arr['p']);
					$validate_tab_arr = $update_product_arr['validate_tab_arr'];
					if($update_product_arr['status'])
					{
						$new_tab_key =  $this->productService->getNewTabKey($input_arr['p'], $input_arr['id']);
						if($new_tab_key == '')
						{
							//If all the completed, then redirect to product list page
							$msg = (empty($this->productService->alert_message))? '' : trans('webshoppack::product.'.$this->productService->alert_message);
							$final_success = isset($update_product_arr['final_success']) ? $update_product_arr['final_success'] : false;
							return \Redirect::to(\Config::get('webshoppack::uri').'/add?id='.$input_arr['id'].'&p=publish')->with('success_message', $msg)->with('final_success', $final_success)->with('validate_tab_arr',$validate_tab_arr);
						}
						else
						{
							//To redirect to next tab..
							return \Redirect::to(\Config::get('webshoppack::uri').'/add?id='.$input_arr['id'].'&p='.$new_tab_key)->with('validate_tab_arr', $validate_tab_arr);
						}
					}
					else
					{
						return \Redirect::to(\Config::get('webshoppack::uri').'/add?id='.$input_arr['id'].'&p=publish')->with('validate_tab_arr', $validate_tab_arr);
					}
				}
				else
				{
					return \Redirect::to(\Config::get('webshoppack::uri').'/add?id='.$input_arr['id'].'&p='.$input_arr['p'])->with('error_message', trans('webshoppack::common.correct_errors'))->withInput()->withErrors($validator);
				}
			}
		}
		return \Redirect::to(\Config::get('webshoppack::uri').'/add');
	}

	public function getProductSubCategories()
	{
		if(\Request::ajax())
		{
			$input_arr = \Input::all();
			$category_id = $input_arr['category_id'];
			if(is_numeric($category_id) && $category_id > 0)
			{
				$sub_categories_arr = $this->productService->getSubCategoryList($category_id);
				$disp_result = '';
				if(count($sub_categories_arr) > 1)
				{
					$disp_result .= '<select id="sub_category_'.$category_id.'" name="sub_category_'.$category_id.'" class="control-label fn_subCat_'.$category_id.'" onchange="listSubCategories(\'sub_category_'.$category_id.'\', \''.$category_id.'\');">';
					foreach($sub_categories_arr AS $category_id => $category_name)
					{
						$disp_result .= '<option value="'.$category_id.'">'.$category_name.'</option>';
					}
					$disp_result .= '</select>';
				}
				else
				{
					$disp_result .= '<div class="fn_clsNoSubCategryFound alert alert-info" id="sub_category_'.$category_id.'" name="sub_category_'.$category_id.'">'.trans('webshoppack::product.product_no_subcategories').'</div>';
				}
				echo $disp_result;
				echo '~~~';
				$cat_list = $this->productService->getAllTopLevelCategoryIds($category_id);
				echo $cat_list.'~~~';
			}
		}
	}

	public function postAddSectionName()
	{
		if(\Request::ajax())
		{
			$input_arr = \Input::all();
			$v_arr = $this->productService->getSectionNameValidation();
			$validator = \Validator::make($input_arr, $v_arr['rules'], $v_arr['messages']);
			if($validator->passes())
			{
				$section_id = $this->productService->addSectionName($input_arr);
				if($section_id == '')
				{
					echo json_encode(array('status'=>'error', 'error_message' => trans("webshoppack::product.invalid_section_name_add_request")));
					return ;
				}
				echo json_encode(array('status'=>'success', 'error_message'=>'', 'section_id' => $section_id, 'section_name' => $input_arr['section_name']));
				return ;
			}
			else
			{
				$errors = $validator->getMessageBag()->toArray();
				echo json_encode(array('status'=>'error', 'error_message' => $errors['section_name']));
				return ;
			}

		}
	}

	public function postProductActions()
	{
		$action = \Input::get('action');
		$p_id = \Input::get('product_id');

		switch($action)
		{
			case 'save_product_thumb_image_title':
				$title = \Input::get('product_image_title');
				echo ($this->productService->saveProductImageTitle($p_id, 'thumb', $title)) ? 'success': 'error';
				$this->productService->updateProductStatus($p_id, 'Draft');
				exit;
				break;

			case 'save_product_default_image_title':
				$title = \Input::get('product_image_title');
				echo ($this->productService->saveProductImageTitle($p_id, 'default', $title)) ? 'success': 'error';
				$this->productService->updateProductStatus($p_id, 'Draft');
				exit;
				break;

			case 'upload_product_thumb_image':
				$title = \Input::get('product_image_title');
				$this->productService->product_media_type = 'image';
				$this->productService->setAllowedUploadFormats('thumb');
				$this->productService->setMaxUploadSize('thumb');

				$file_info = array();
				$file = \Input::file('uploadfile');
				$upload_file_name = $file->getClientOriginalName();
				$upload_status = $this->productService->uploadMediaFile('uploadfile', 'image', $file_info);
				if ($upload_status['status'] == 'success')
				{
					$this->productService->updateItemProductImage($p_id, $title, $file_info);

					$image_dim = CUtil::DISP_IMAGE(145, 145, $file_info['t_width'], $file_info['t_height'], true);
					echo json_encode(array('status'=>'success',
									'server_url'=>$file_info['server_url'],
									'filename'=>$file_info['filename_no_ext'] .'T.'.$file_info['ext'] ,
									't_width'=>$image_dim['width'],
									't_height'=>$image_dim['height'],
									'title'=>$file_info['title']
									));
					$this->productService->updateProductStatus($p_id, 'Draft');
				}
				else
				{
					echo json_encode(array('status'=>'error', 'error_message'=>$upload_status['error_message'], 'filename'=>$upload_file_name));
				}
				exit;
				break;

			case 'upload_item_default_image':
				$title = \Input::get('product_image_title');
				$this->productService->product_media_type = 'image';
				$this->productService->setAllowedUploadFormats('default');
				$this->productService->setMaxUploadSize('default');

				$file_info = array();
				$file = \Input::file('uploadfile');
				$upload_file_name = $file->getClientOriginalName();
				$upload_status = $this->productService->uploadMediaFile('uploadfile', 'image', $file_info);
				if ($upload_status['status'] == 'success')
				{
					$this->productService->updateProductDefaultImage($p_id, $title, $file_info);
					$image_dim = CUtil::DISP_IMAGE(578, 385, $file_info['l_width'], $file_info['l_height'], true);
					echo json_encode(array('status'=>'success',
									'server_url'=>$file_info['server_url'],
									'filename'=>$file_info['filename_no_ext'] .'L.'.$file_info['ext'] ,
									't_width'=>$image_dim['width'],
									't_height'=>$image_dim['height'],
									'title'=>$file_info['title']
									));
					$this->productService->updateProductStatus($p_id, 'Draft');
				}
				else
				{
					echo json_encode(array('status'=>'error', 'error_message'=>$upload_status['error_message'], 'filename'=>$upload_file_name));
				}
				exit;
				break;

			case 'remove_default_thumb_image':
				echo ($this->productService->removeProductThumbImage($p_id)) ? 'success': 'error';
				$this->productService->updateProductStatus($p_id, 'Draft');
				exit;
				break;

			case 'remove_default_image':
				echo ($this->productService->removeProductDefaultImage($p_id)) ? 'success': 'error';
				$this->productService->updateProductStatus($p_id, 'Draft');
				exit;
				break;

			case 'upload_resource_preview': // images on the image tab
				$resource_type = \Input::get('resource_type');
				$this->productService->setProductPreviewType($p_id);
				$this->productService->setAllowedUploadFormats('preview');
				$this->productService->setMaxUploadSize('preview');

				$resource_count = ProductResource::whereRaw('product_id = ? AND resource_type = ? ', array($p_id, $this->productService->product_media_type))->count();
				if($resource_count < \Config::get('webshoppack::preview_max'))
				{
					$file_info = array();
					$file = \Input::file('uploadfile');
					$upload_file_name = $file->getClientOriginalName();
					$upload_status = $this->productService->uploadMediaFile('uploadfile',  $this->productService->product_media_type, $file_info);
					if ($upload_status['status'] == 'success')
					{
						$resource_arr = array(
							'product_id'=>$p_id,
							'resource_type'=>$resource_type, // hard coded
							'filename'=>$file_info['filename_no_ext'],
							'ext'=>$file_info['ext'],
							'title'=>$file_info['title'],
							'width'=>$file_info['width'],
							'height'=>$file_info['height'],
							't_width'=>$file_info['t_width'],
							't_height'=>$file_info['t_height'],
							'l_width'=>$file_info['l_width'],
							'l_height'=>$file_info['l_height'],
							'server_url'=>$file_info['server_url'],
							'is_downloadable'=>$file_info['is_downloadable']
					 	);

						$resource_id = $this->productService->insertResource($resource_arr);
						$image_dim = CUtil::DISP_IMAGE(74, 74, $file_info['t_width'], $file_info['t_height'], true);

						$this->productService->updateProductStatus($p_id, 'Draft');
						echo json_encode(array('status' => 'success',
										'resource_type' => ucwords($resource_type),
										'server_url' => $file_info['server_url'],
										'filename' => $file_info['file_thumb'],
										't_width' => $image_dim['width'],
										't_height' => $image_dim['height'],
										'title' => $file_info['title'],
										'resource_id' => $resource_id
										));

					}
					else
					{
						echo json_encode(array('status'=>'error', 'error_message'=>$upload_status['error_message'], 'filename'=>$upload_file_name));
					}
				}
				else
				{
					echo json_encode(array('status'=>'error', 'error_message'=> trans('webshoppack::products_max_file'), 'filename'=> ''));
				}

			exit;
			break;

		case 'save_resource_title':
			$row_id = \Input::get('row_id');
			$resource_title = \Input::get('resource_title');

			echo ($this->productService->updateProductResourceImageTitle($row_id, $resource_title)) ? 'success': 'error';
			$this->productService->updateProductStatus($p_id, 'Draft');
			exit;
			break;

		case 'delete_resource':
			$row_id = \Input::get('row_id');
			if($this->productService->deleteProductResource($row_id))
			{
				$this->productService->updateProductStatus($p_id, 'Draft');
				echo json_encode(array(	'result'=>'success','row_id'=> $row_id));
			}
			else
			{
				echo json_encode(array(	'result'=>'failed','row_id'=> $row_id));
			}

			exit;
			break;

		case 'order_resource':
			$resourcednd_arr = \Input::get('resourcednd');
			$this->productService->updateProductResourceImageDisplayOrder($resourcednd_arr);
			// set status is not called since only re-ordering
			exit;
			break;

		case 'upload_resource_file': // the download file in zip format
			$resource_type = 'Archive';
			$this->productService->product_media_type = 'archive';
			$this->productService->setAllowedUploadFormats('archive');
			$this->productService->setMaxUploadSize('archive');

			$resource_count = ProductResource::whereRaw('product_id = ? AND resource_type = ? ', array($p_id, $this->productService->product_media_type))->count();
			if($resource_count == 0)
			{
				$file_info = array();
				$file = \Input::file('uploadfile');
				$upload_file_name = $file->getClientOriginalName();
				$upload_status = $this->productService->uploadMediaFile('uploadfile',$this->productService->product_media_type, $file_info,  true);
				if ($upload_status['status'] == 'success') {
					$resource_arr = array(
						'product_id'=>$p_id,
						'resource_type'=>$resource_type,
						'server_url'=>$file_info['server_url'],
						'filename'=>$file_info['filename_no_ext'],
						'ext'=>$file_info['ext'],
						'title'=>$file_info['title'],
						'width'=>$file_info['width'],
						'height'=>$file_info['height'],
						't_width'=>$file_info['t_width'],
						't_height'=>$file_info['t_height'],
						'l_width'=>$file_info['l_width'],
						'l_height'=>$file_info['l_height'],
						'is_downloadable'=>$file_info['is_downloadable']
				 	);

					$resource_id = $this->productService->insertResource($resource_arr);

					if ($file_info['title'] != '')
					{
						$download_filename = preg_replace('/[^0-9a-z\.\_\-)]/i', '', $file_info['title']) . '.' . $file_info['ext'];
					}
					else
					{
						$download_filename = md5($p_id) . '.' .$file_info['ext'];
					}

					echo json_encode(array('status'=>'success',
									'server_url'=>$file_info['server_url'],
									'download_url'=> \URL::action('Agriya\Webshoppack\ProductAddController@getProductActions'). '?action=download_file&product_id=' . $p_id,
									'filename'=>$download_filename ,
									't_width'=>$file_info['t_width'],
									't_height'=>$file_info['t_height'],
									'title'=>$file_info['title'],
									'resource_id'=>$resource_id,
									'is_downloadable'=>$file_info['is_downloadable']
									));
					$this->productService->updateProductStatus($p_id, 'Draft');
				}
				else
				{
					echo json_encode(array('status'=>'error', 'error_message'=>$upload_status['error_message'], 'filename'=>$upload_file_name));
				}
			}
			else
			{
				echo json_encode(array('status'=>'error', 'error_message'=> trans('webshoppack::product.products_max_file'), 'filename'=> ''));
			}

			exit;
			break;

		}
	}

	public function getProductActions()
	{
		$action = \Input::get('action');
		$p_id = \Input::get('product_id');
		switch($action)
		{
			case 'download_file':
				$this->productService->downloadProductResouceFile($p_id, true);
				exit;
				break;
		}
	}
}