<?php namespace Agriya\Webshoppack;

class AdminProductCategoryController extends \BaseController
{
	protected $isStaffProtected = 1;
    protected $page_arr = array();
	protected $action_arr = array();

	function __construct()
	{
        $this->adminProductCategoryService = new AdminProductCategoryService();
    }

	public function postCategoryInfo($errors = array())
	{
		$root_category_id = $this->adminProductCategoryService->getRootCategoryId();
		$parent_category_id = $root_category_id;
		if (\Input::get('parent_category_id') && \Input::get('parent_category_id') != "")
		{
			$parent_category_id = \Input::get('parent_category_id');
		}
		$d_arr['edit_form'] =  false;
		$d_arr['add_edit_mode_text'] = trans('webshoppack::admin/manageCategory.add_title');
		$category_info = array();
		if(count($errors) == 0)
			$category_info['status'] = "active";
		$category_image_details = array();
		$sel_category_id = $parent_category_id;
		$cat_url = \URL::action('Agriya\Webshoppack\AdminProductCategoryController@postAdd');
		if (\Input::get('category_id') && \Input::get('category_id') != $root_category_id)
		{
			$category_info = $this->adminProductCategoryService->populateCategory(\Input::get('category_id'));
	        if (count($category_info) > 0)
			{
				$cat_url = \URL::action('Agriya\Webshoppack\AdminProductCategoryController@postEdit');
				$parent_category_id = $category_info['parent_category_id'];
				$sel_category_id = $parent_category_id;
				$d_arr['edit_form'] = true;
				$d_arr['add_edit_mode_text'] = trans('webshoppack::admin/manageCategory.edit_title');
		    }
	    }
	    $parent_category_name = $this->adminProductCategoryService->getParentCategoryName($sel_category_id);
	    $d_arr['parent_category_id'] =  $parent_category_id;
	    $d_arr['parent_category_name'] =  $parent_category_name;
	    $d_arr['category_id'] =  \Input::get('category_id');
	    $d_arr['root_category_id'] =  $root_category_id;
	    $success_msg = "";
	    if(isset($_SESSION['category_info_success_msg']) && $_SESSION['category_info_success_msg'])
		{
			$success_msg = $_SESSION['category_info_success_msg'];
			unset($_SESSION['category_info_success_msg']);
		}
		if(count($errors) > 0)
			return \View::make('webshoppack::admin.productCategoryInfo', compact('d_arr', 'category_info', 'category_image_details', 'cat_url', 'success_msg', 'errors'));
		else
			return \View::make('webshoppack::admin.productCategoryInfo', compact('d_arr', 'category_info', 'category_image_details', 'cat_url', 'success_msg'));
	}

	public function postAdd()
	{
		$input_arr = \Input::All();
		$validator_arr = $this->adminProductCategoryService->productCategoryValidation($input_arr, 0, $input_arr['parent_category_id']);

		$validator = \Validator::make($input_arr, $validator_arr['rules'], $validator_arr['messages']);
		if($validator->passes())
		{
			$category_id = $this->adminProductCategoryService->addCategory($input_arr);
			$_SESSION['category_info_success_msg'] = trans('webshoppack::admin/manageCategory.add-category.add_category_success_msg');
			echo '|##|true|##|'.$category_id.'|##|';
			exit;
		}
		else
		{
			$errors = $validator->errors();
			return $this->postCategoryInfo($errors);
		}
	}

	public function postEdit()
	{
		$input_arr = \Input::All();
		$validator_arr = $this->adminProductCategoryService->productCategoryValidation($input_arr, $input_arr['category_id'], $input_arr['parent_category_id']);
		$validator = \Validator::make($input_arr, $validator_arr['rules'], $validator_arr['messages']);
		if($validator->passes())
		{
			$this->adminProductCategoryService->updateCategory($input_arr);
			echo '|##|true|##|'.$input_arr['category_id'].'|##|';
			$_SESSION['category_info_success_msg'] = trans('webshoppack::admin/manageCategory.add-category.update_category_success_msg');
			exit;
		}
		else
		{
			$errors = $validator->errors();
			return $this->postCategoryInfo($errors);
		}
	}

	public function getDeleteCategory()
	{
		$result_arr = $this->adminProductCategoryService->deleteCategory(\Input::get('category_id'));
		echo json_encode($result_arr);
	}

	public function getDeleteCategoryImage()
	{
		$resource_id 	= \Input::get("resource_id");
		$imagename 		= \Input::get("imagename");
		$imageext 		= \Input::get("imageext");
		$imagefolder 	= \Input::get("imagefolder");

		if($imagename != "")
		{
			$delete_status = $this->adminProductCategoryService->deleteCategoryImage($resource_id, $imagename, $imageext, \Config::get($imagefolder));
			if($delete_status)
			{
				return \Response::json(array('result' => 'success'));
			}
		}
		return \Response::json(array('result' => 'error'));
	}
}