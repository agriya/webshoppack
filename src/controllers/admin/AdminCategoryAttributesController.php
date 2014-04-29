<?php namespace Agriya\Webshoppack;

class AdminCategoryAttributesController extends \BaseController
{
	protected $isStaffProtected = 1;
    protected $page_arr = array();
	protected $action_arr = array();

	function __construct()
	{
        $this->adminCategoryAttributesService = new AdminCategoryAttributesService();
    }

    public function postAttributesInfo()
    {
    	$category_id = \Input::get('category_id');
    	$this->root_category_id = $this->adminCategoryAttributesService->getRootCategoryId();
    	$attribs_arr = $this->adminCategoryAttributesService->populateAttributes($category_id);
    	$d_arr['category_id'] =  $category_id;
	    $d_arr['root_category_id'] =  $this->root_category_id;
	    $attr_service_obj = $this->adminCategoryAttributesService;
    	return \View::make('webshoppack::admin.categoryAttributesManagement', compact('attribs_arr', 'd_arr', 'attr_service_obj'));
	}

	public function postAdd()
	{
		$input_arr = \Input::All();
		$category_id = \Input::get('category_id');
		$attribute_id = \Input::get('attribute_id');
		if(!$this->adminCategoryAttributesService->isCategoryExists($category_id))
		{
			$result_arr = array('err' => true, 'err_msg' => trans('webshoppack::admin/manageCategory.delete-category.category_not_found_msg'));
		}
		else if(!$this->adminCategoryAttributesService->isAttributeExists($attribute_id))
		{
			$result_arr = array('err' => true, 'err_msg' => trans('webshoppack::admin/manageCategory.attributes_not_found_err_msg'));
		}
		else
		{
			$result_arr = $this->adminCategoryAttributesService->assignAttribute($input_arr);
		}
		echo json_encode($result_arr);
	}

	public function getDeleteAttributes()
	{
		$category_id = \Input::get('category_id');
		$attribute_id = \Input::get('attribute_id');
		if(!$this->adminCategoryAttributesService->isCategoryExists($category_id))
		{
			$result_arr = array('err' => true, 'err_msg' => trans('webshoppack::admin/manageCategory.delete-category.category_not_found_msg'));
		}
		else if(!$this->adminCategoryAttributesService->isAttributeExists($attribute_id))
		{
			$result_arr = array('err' => true, 'err_msg' => trans('webshoppack::admin/manageCategory.attributes_not_found_err_msg'));
		}
		else
		{
			$result_arr = $this->adminCategoryAttributesService->removeAttribute($category_id, $attribute_id);
		}
		echo json_encode($result_arr);
	}

	public function getViewAttribute()
	{
		$attribute_id = \Input::get('attribute_id');
		$attribute_details = $this->adminCategoryAttributesService->populateAttributes('', $attribute_id);
		$attr_service_obj = $this->adminCategoryAttributesService;
		return \View::make('webshoppack::admin.viewAttribute', compact('attribute_details', 'attr_service_obj'));
	}

	public function getAttributesOrder()
	{
		$input_arr = \Input::All();
		$this->adminCategoryAttributesService->updateListRowOrder($input_arr);
	}
}