<?php namespace Agriya\Webshoppack;

class AdminProductAttributesController extends \BaseController
{
	protected $isStaffProtected = 1;
    protected $page_arr = array('getIndex' => 'product_attributes_list');
	protected $action_arr = array();
	public $option_fields = array('select', 'check', 'option', 'multiselectlist');

	function __construct()
	{
        $this->adminProductAttributesService = new AdminProductAttributesService();
    }

    public function getIndex()
    {
    	$attribute_details = array();
    	$q = $this->adminProductAttributesService->buildProductAttributesQuery();
		$perPage = \Config::get('webshoppack::attribute_per_page_list');
		$attribute_details 	= $q->paginate($perPage);
		$prod_attr_service_obj = $this->adminProductAttributesService;
		$options = $this->option_fields;
		$d_arr['attribute_is_searchable'] = 'no';
		$d_arr['status'] = 'active';
		$ui_elements_all = array_merge(\Config::get('webshoppack::ui_no_options'), \Config::get('webshoppack::ui_options'));
		$pageTitle = "Admin - ".trans('webshoppack::admin/manageCategory.product_attribute_title');
		$d_arr['pageTitle'] = $pageTitle;
    	return \View::make('webshoppack::admin.productAttributesManagement', compact('attribute_details', 'options', 'prod_attr_service_obj', 'ui_elements_all', 'd_arr'));
	}

	public function postAdd()
	{
		$input_arr = \Input::All();
		$rules = array('attribute_label'	=> $this->adminProductAttributesService->getValidatorRule('attribute_label'),
					   'attribute_question_type' => $this->adminProductAttributesService->getValidatorRule('attribute_question_type')
						);
		$v = \Validator::make(\Input::all(), $rules);
		if ( $v->passes())
		{
			echo json_encode($this->adminProductAttributesService->addAttribute($input_arr));
		}
		else
		{
	        echo json_encode(array('err' => true, 'err_msg' => trans('webshoppack::common.correct_errors')));
	    }
	}

	public function getAttributesRow()
	{
		$row_id = \Input::get('row_id');
		echo json_encode($this->adminProductAttributesService->getListRow($row_id));
	}

	public function postUpdate()
	{
		$input_arr = \Input::All();
		$attribute_id = \Input::get('attribute_id');
		if(!$this->adminProductAttributesService->isAttributeExists($attribute_id))
		{
			$result_arr = array('err' => true, 'err_msg' => trans('webshoppack::admin/manageCategory.attributes_not_found_err_msg'));
		}
		else
		{
			$rules = array('attribute_label'	=> $this->adminProductAttributesService->getValidatorRule('attribute_label'),
					   'attribute_question_type' => $this->adminProductAttributesService->getValidatorRule('attribute_question_type')
						);
			$v = \Validator::make(\Input::all(), $rules);
			if ( $v->passes())
			{
				$result_arr = $this->adminProductAttributesService->updateListRow($attribute_id, $input_arr);
			}
			else
			{
				$result_arr = array('err' => true, 'err_msg' => trans('webshoppack::common.correct_errors'));
		    }
		}
		echo json_encode($result_arr);
	}

	public function getAttributesDelete()
	{
		$row_id = \Input::get('row_id');
		if($this->adminProductAttributesService->isOptionsAlreadyUsed($row_id))
		{
			echo json_encode(array('result'=>'failed', 'row_id'=> $row_id, 'err_msg' => trans('webshoppack::admin/manageCategory.delete-attribute.attribute_options_in_use_err')));
		}
		else if($this->adminProductAttributesService->deleteListRow($row_id))
		{
			echo json_encode(array(	'result'=>'success','row_id'=> $row_id));
		}
		else
		{
			echo json_encode(array(	'result'=>'failed','row_id'=> $row_id));
		}
	}
}