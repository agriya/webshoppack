<?php namespace Agriya\Webshoppack;

class AdminManageProductCatalogController extends \BaseController
{
	protected $isStaffProtected = 1;
    protected $page_arr = array('getIndex' => 'product_categories_list');
	protected $action_arr = array();

	function __construct()
	{
        $this->adminProductCatalogService = new AdminManageProductCatalogService();
    }

    public function getIndex()
	{
		$root_category_id = $this->adminProductCatalogService->insertRootCategory();
		$ajax_page = false;
		$pageTitle = "Admin - ".trans('webshoppack::admin/manageCategory.manage_product_catalog_title');
		return \View::make('webshoppack::admin.productCategoryTree', compact('root_category_id', 'ajax_page', 'pageTitle'));
	}

	public function getCategoryTreeDetails()
	{
		$this->adminProductCatalogService->getProductCount();
		return $this->adminProductCatalogService->get_children(array('category_id' => \Input::get('category_id')));
	}

	public function postCategoryDetailsBlock()
	{
		if(\Input::get('display_block') == 'category_details' ||
			\Input::get('display_block') == 'add_sub_category')
		{
			$ajax_page = true;
			$category_id = \Input::get('category_id');
			$display_block = \Input::get('display_block');
			$root_category_id = $this->adminProductCatalogService->getRootCategoryId();
			$category_details = $this->adminProductCatalogService->getCategoryDetails($category_id);
			return \View::make('webshoppack::admin.manageProductCatalogTabs', compact('category_details', 'category_id', 'ajax_page', 'root_category_id', 'display_block'));
		}
	}
}