<?php namespace Agriya\Webshoppack;

class AdminProductCategoryService extends AdminManageProductCatalogService
{
	public function populateCategory($category_id)
	{
		$category_details = array();
		$cat_info = ProductCategory::Select('category_name', 'seo_category_name', 'category_description', 'parent_category_id', 'status', 'id',
					'available_sort_options', 'is_featured_category', 'image_name', 'image_ext', 'image_width', 'image_height', 'category_meta_title', 'category_meta_keyword', 'category_meta_description')->whereRaw('id = ?', array($category_id))->first();
		if (count($cat_info) > 0)
	    {
	    	$category_details = $cat_info;
			$available_sort_options = explode(',', $cat_info['available_sort_options']);
			$category_details['available_sort_options'] = $available_sort_options;
			if($cat_info['available_sort_options'] == 'all')
			{
				$category_details['use_all_available_sort_options'] = 'Yes';
			}
			else
			{
				$category_details['use_all_available_sort_options'] = '';
			}
			$category_details['image_name'] = $cat_info['image_name'];
			$category_details['image_ext'] = $cat_info['image_ext'];
			$category_details['image_width'] = $cat_info['image_width'];
			$category_details['image_height'] = $cat_info['image_height'];
	    }
		return $category_details;
	}

	public function productCategoryValidation($input_arr, $cat_id = 0, $parent_cat_id = 0)
	{
		$rules_arr = array(
				'category_name' => 'Required|unique:product_category,category_name,'.$cat_id.',id,parent_category_id,'.$parent_cat_id,
				'seo_category_name' => 'Required|IsValidSlugUrl:'.$input_arr['seo_category_name'].'|unique:product_category,seo_category_name,'.$cat_id.',id,parent_category_id,'.$parent_cat_id,
				'status' => 'Required',
				'category_image' => 'mimes:'.\Config::get("webshoppack::product_category_uploader_allowed_extensions"),
				//|max:' . Config::get('mp_productCategory.product_category_image_uploader_allowed_file_size')
		);

		$message = array('category_name.unique' => trans('webshoppack::admin/manageCategory.add-category.category_exists_msg'),
						'seo_category_name.is_valid_slug_url' => trans('webshoppack::admin/manageCategory.add-category.invalid_slug_url'),
						'seo_category_name.unique' => trans('webshoppack::admin/manageCategory.add-category.slug_url_exists_msg'),
						'category_image.mimes' => trans('webshoppack::common.uploader_allow_format_err_msg'),
						'category_image.size' => trans('webshoppack::common.uploader_max_file_size_err_msg'),
						);
		return array('rules' => $rules_arr, 'messages' => $message);
	}

	public function getNodeInfo($id)
	{
		$cat_info = ProductCategory::Select('category_left', 'category_right', 'category_level')->whereRaw('id = ?', array($id))->first();
		if(count($cat_info) > 0)
		{
			return array($cat_info['category_left'], $cat_info['category_right'], $cat_info['category_level']);
		}
		return false;
	}

	public function getCategoryLevel($parent_category_id = 0)
	{
		if($parent_category_id)
		{
			$cat_level_details = ProductCategory::Select('category_level')->whereRaw('id = ?', array($parent_category_id))->first();
			if(count($cat_level_details) > 0)
			{
				return $cat_level_details['category_level'] + 1;
			}
			return 1;
		}
		return 1;
	}

	public function addCategory($data_arr)
	{
		if (list($left_id, $right_id, $level) = $this->getNodeInfo($data_arr['parent_category_id']))
		{
			ProductCategory::where('category_right', '>=', $right_id)->update(array("category_left" => \DB::raw('IF(category_left > '.$right_id. ',category_left + 2,category_left)'), "category_right" => \DB::raw('IF(category_right >= '.$right_id. ',category_right + 2, category_right)')));

			$data_input_arr['seo_category_name'] = $data_arr['seo_category_name'];
			$data_input_arr['category_name'] = $data_arr['category_name'];
			$data_input_arr['category_description'] = $data_arr['category_description'];
			$data_input_arr['category_meta_title'] = $data_arr['category_meta_title'];
			$data_input_arr['category_meta_description'] = $data_arr['category_meta_description'];
			$data_input_arr['category_meta_keyword'] = $data_arr['category_meta_keyword'];
			$data_input_arr['category_level'] = $this->getCategoryLevel($data_arr['parent_category_id']);
			$data_input_arr['category_left'] = $right_id;
			$data_input_arr['category_right'] = $right_id + 1;
			// available sort by options
			$available_sort_by_options = '';
			if($data_arr['use_all_available_sort_options'] == 'Yes')
			{
				$available_sort_by_options = 'all';
			}
			$data_input_arr['available_sort_options'] = $available_sort_by_options;
			$data_input_arr['date_added'] = 'Now()';
			$data_input_arr['parent_category_id'] = $data_arr['parent_category_id'];
			$data_input_arr['status'] = $data_arr['status'];
			$img_arr = array();
			if (\Input::hasFile('category_image'))
			{
				$file = \Input::file('category_image');
				$image_ext = $file->getClientOriginalExtension();
				$image_name = \Str::random(20);
				$destinationpath = \URL::asset(\Config::get("webshoppack::product_category_image_folder"));
				$img_arr = $this->uploadCategoryImage($file, $image_ext, $image_name, $destinationpath, 0, 'add');
			}

			$category_id = ProductCategory::insertGetId(array_merge($data_input_arr, $img_arr));
			return $category_id;
		}
	}

	public function uploadCategoryImage($file, $image_ext, $image_name, $destinationpath, $reference_id, $mode)
	{
		$return_arr = array();
		$config_path = \Config::get('webshoppack::product_category_image_folder');
		CUtil::chkAndCreateFolder($config_path);

		// open file a image resource
		\Image::make($file->getRealPath())->save(\Config::get("webshoppack::product_category_image_folder").$image_name.'_O.'.$image_ext);

		list($width,$height)= getimagesize($file);
		list($upload_img['width'], $upload_img['height']) = getimagesize(base_path().'/public/'.$config_path.$image_name.'_O.'.$image_ext);

		$thumb_width = \Config::get("webshoppack::product_category_image_thumb_width");
		$thumb_height = \Config::get("webshoppack::product_category_image_thumb_height");
		if(isset($thumb_width) && isset($thumb_height))
		{
			$timg_size = CUtil::DISP_IMAGE($thumb_width, $thumb_height, $upload_img['width'], $upload_img['height'], true);
			\Image::make($file->getRealPath())
				->resize($thumb_width, $thumb_height, true, false)
				->save($config_path.$image_name.'_T.'.$image_ext);
		}

		$img_path = base_path().'/public/'.$config_path;
		list($upload_input['thumb_width'], $upload_input['thumb_height']) = getimagesize($img_path.$image_name.'_T.'.$image_ext);
		if($mode == 'edit')
		{
			$this->deleteExistingImageFiles($reference_id);
		}
		$return_arr = array('image_ext' => $image_ext, 'image_name' => $image_name, 'image_width' => $upload_input['thumb_width'], 'image_height' => $upload_input['thumb_height']);
		return $return_arr;
	}

	public function deleteExistingImageFiles($reference_id)
	{
		$existing_images = ProductCategory::where('id', '=', $reference_id)->first();

		if(count($existing_images) > 0 && $existing_images['image_name'] != '')
		{
			$data_arr = array('image_name' => '', 'image_ext' => '', 'image_height' => '', 'image_width' => '');
			$affectedRows = ProductCategory::whereRaw('id = ?', array($reference_id))->update($data_arr);
			$this->deleteImageFiles($existing_images['image_name'], $existing_images['image_ext'], \Config::get("webshoppack::product_category_image_folder"));
		}
	}

	public function updateCategory($input_arr)
	{
		$data_arr['category_name'] = $input_arr['category_name'];
		$data_arr['seo_category_name'] = $input_arr['seo_category_name'];
		$data_arr['category_description'] = $input_arr['category_description'];
		$data_arr['category_meta_title'] = $input_arr['category_meta_title'];
		$data_arr['category_meta_description'] = $input_arr['category_meta_description'];
		$data_arr['category_meta_keyword'] = $input_arr['category_meta_keyword'];
		$data_arr['category_level'] = $this->getCategoryLevel($input_arr['parent_category_id']);
		$data_arr['parent_category_id'] = $input_arr['parent_category_id'];
		$data_arr['status'] = $input_arr['status'];
		// available sort by options
		$available_sort_by_options = '';
		if($input_arr['use_all_available_sort_options'] == 'Yes')
		{
			$available_sort_by_options = 'all';
		}
		$data_arr['available_sort_options'] = $available_sort_by_options;
		$img_arr = array();
		if (\Input::hasFile('category_image'))
		{
			$file = \Input::file('category_image');
			$image_ext = $file->getClientOriginalExtension();
			$image_name = \Str::random(20);
			$destinationpath = \URL::asset(\Config::get("webshoppack::product_category_image_folder"));
			$img_arr = $this->uploadCategoryImage($file, $image_ext, $image_name, $destinationpath, $input_arr['category_id'], 'edit');
		}
		ProductCategory::whereRaw('id = ?', array($input_arr['category_id']))->update(array_merge($data_arr, $img_arr));
	}

	public function isCategoryExists($category_id)
	{
		$category_count = ProductCategory::whereRaw('id = ?', array($category_id))->count();
	    return $category_count;
	}

	public function getSubCategoryIds($category_id)
	{
		$sub_category_ids = 0;
		$sub_cat_details = \DB::select('select node.id AS sub_category_id from product_category node, product_category parent where
				node.category_left BETWEEN parent.category_left AND parent.category_right AND parent.id = ? ORDER BY node.category_left', array($category_id));
		if (count($sub_cat_details) > 0)
		{
			foreach($sub_cat_details as $sub_cat)
			{
				$sub_category_ids = ($sub_category_ids)?($sub_category_ids . ',' .$sub_cat->sub_category_id ):$sub_cat->sub_category_id;
			}
		}
		return $sub_category_ids;
	}

	public function isCategoryProductExists($category_id)
	{
		// Get sub category ids
		$this->sub_category_ids = $this->getSubCategoryIds($category_id);
		if(!$this->sub_category_ids)
			$this->sub_category_ids = $category_id;

		$sub_category_ids = explode(',', $this->sub_category_ids);
		$product_count = Product::whereIn('product_category_id', $sub_category_ids)->count();
	    return $product_count;
	}

	public function deleteCategory($del_category_id)
	{
		// check category exist or not
		if(!$this->isCategoryExists($del_category_id))
		{
			$result_arr = array('err' => true, 'err_msg' => trans('webshoppack::admin/manageCategory.delete-category.category_not_found_msg'));
			return $result_arr;
		}

		// check products added for the selected category or its subcategories
		if($this->isCategoryProductExists($del_category_id))
		{
			$result_arr = array('err' => true, 'err_msg' => trans('webshoppack::admin/manageCategory.delete-category.category_in_use_msg'));
			return $result_arr;
		}

		// delete category details in all assigned attributes & category image.
		$cat_sub_category_ids = explode(',', $this->sub_category_ids);
		$cat_details = ProductCategory::whereIn('id', $cat_sub_category_ids)->get(array('id', 'image_name', 'image_ext'));

		if(count($cat_details) > 0)
		{
			foreach($cat_details as $cat)
			{
				// Delete all attributes assigned to the selected category & its subcategories
				ProductCategoryAttributes::whereRaw('category_id = ?', array($cat->id))->delete();

				// Delete category image
				$this->deleteImageFiles($cat->image_name, $cat->image_ext, \Config::get("webshoppack::product_category_image_folder"));
			}
		}

		//store the values of the left and right of the category to be deleted
		//delete all those cateogries b/w the above 2
		// update the cateogies to the right of the deleted category  - reduce left and right bu width of the deleted category
		$cat_info = ProductCategory::Select('category_left', 'category_right')->whereRaw('id = ?', array($del_category_id))->first();
		if(count($cat_info) > 0)
		{
			$category_left = $cat_info['category_left'];
			$category_right = $cat_info['category_right'];
			$width = $category_right - $category_left + 1;

			ProductCategory::whereRaw(\DB::raw('category_left  between  '. $category_left.' AND '.$category_right))->delete();

			//To update category left
			ProductCategory::whereRaw(\DB::raw('category_left >  '.$category_right))->update(array("category_left" => \DB::raw('category_left - '. $width)));

			//To update category right
			ProductCategory::whereRaw(\DB::raw('category_right >  '.$category_right))->update(array("category_right" => \DB::raw('category_right - '. $width)));
		}
		$result_arr = array('err' => false, 'err_msg' => '', 'category_id' => $del_category_id);
		return $result_arr;
	}

	public function deleteCategoryImage($id, $filename, $ext, $folder_name)
	{
		$data_arr = array('image_name' => '', 'image_ext' => '', 'image_height' => '', 'image_width' => '');
		$affectedRows = ProductCategory::whereRaw('id = ?', array($id))->update($data_arr);
		if($affectedRows)
		{
			$this->deleteImageFiles($filename, $ext, $folder_name);
			return true;
		}
		return false;
	}

	public function deleteImageFiles($filename, $ext, $folder_name)
	{
		if (file_exists($folder_name.$filename."_T.".$ext))
		{
			unlink($folder_name.$filename."_T.".$ext);
		}
		if (file_exists($folder_name.$filename."_O.".$ext))
		{
			unlink($folder_name.$filename."_O.".$ext);
		}
	}
}