<?php namespace Agriya\Webshoppack;

class AdminManageProductCatalogService
{
	protected $root_category_id = 0;
	public $prod_cat_count_arr = array();
	public function insertRootCategory()
	{
		$id = 0;
		$root_count = ProductCategory::whereRaw('category_level = 0 AND parent_category_id = 0')->count();
		if($root_count > 0)
		{
			$result = ProductCategory::Select('id')->whereRaw('category_level = 0 AND parent_category_id = 0')->first();
			$id = $result['id'];
		}
		else
		{
			$arr['seo_category_name'] = "Root";
			$arr['category_left'] = 1;
			$arr['category_right'] = 2;
			$arr['category_level'] = 0;
			$id = ProductCategory::insertGetId($arr);
		}
		return $id;
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

	private function _get_children($category_id)
	{
		$children = array();

		$cat_details = ProductCategory::whereRaw('parent_category_id = ?', array($category_id))->orderBy('category_left', 'ASC')->get(array('id', 'category_name', 'category_left', 'category_right',
											'parent_category_id', 'category_level', 'display_order'));
		if(count($cat_details) > 0)
		{
			foreach($cat_details as $cat)
			{
				$children[$cat->id] = $cat;
			}
		}
		return $children;

	}

	public function get_children($data)
	{
		$tmp = $this->_get_children((int)$data['category_id']);
		$result = array();
		if((int)$data['category_id'] === (int)$this->getRootCategoryId() && count($tmp) === 0)
		{
			$result[] = array(
				'attr' => '',
				'data' => trans('webshoppack::admin/manageCategory.no_category_msg'),
				'state' => ''
			);
		}

		if((int)$data['category_id'] === 0)
			return json_encode($result);


		foreach($tmp as $key => $value)
		{
			$category_id = $value['id'];
			//show the product count in () if set
			if(isset($this->prod_cat_count_arr[$category_id]))
				$data = $value['category_name'].' ('.$this->prod_cat_count_arr[$category_id].')';
			else
				$data = $value['category_name'];
			$result[] = array(
				'attr' => array('category_id' => 'node_'.$category_id, 'id' => 'node_'.$category_id),
				'data' => $data,
				'state' => ((int)$value['category_right'] - (int)$value['category_left'] > 1) ? 'closed' : ''
			);
		}
		return json_encode($result);
	}

	public function getProductCount()
	{
		$product_details = \DB::select('select parent.id AS category_id, COUNT(prod.id) product_count from product_category node, product_category parent, product prod where
				node.category_left BETWEEN parent.category_left AND parent.category_right AND node.id = prod.product_category_id AND product_status != \'Deleted\'
				GROUP BY parent.id ORDER BY node.category_left');
		if (count($product_details)) {
			foreach($product_details as $product)
			{
				$this->prod_cat_count_arr[$product->category_id] = $product->product_count;
			}
		}
	}

	public function getCategoryDetails($cat_id)
	{
		$category_details = array();
		$cat_details = ProductCategory::select('category_name', 'parent_category_id', 'id')->whereRaw('id = ?', array($cat_id))->first();
		if(count($cat_details) > 0)
		{
			$cat_details['full_parent_category_name'] = $this->getParentCategoryName($cat_details['id']);
			$category_details = $cat_details;
		}
		return $category_details;
	}

	public function getParentCategoryName($category_id)
	{
		$parent_category_name = '';
		$cat_details = \DB::select('select parent.category_name, parent.id from product_category node, product_category parent where
				node.category_left BETWEEN parent.category_left AND parent.category_right AND node.id = ? ORDER BY parent.category_left', array($category_id));
		if (count($cat_details) > 0)
		{
			foreach($cat_details as $cat)
			{
				$parent_category_name = ($parent_category_name)?($parent_category_name . ' > ' .$cat->category_name ):$cat->category_name;
			}
		}
		return $parent_category_name;
	}
}