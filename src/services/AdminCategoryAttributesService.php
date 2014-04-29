<?php namespace Agriya\Webshoppack;

class AdminCategoryAttributesService extends AdminProductCategoryService
{
	public $parent_category_attributes = array();
	public $option_fields = array('select', 'check', 'option', 'multiselectlist');

	public function populateAttributes($category_id = '', $sel_attribute = '')
	{
		if($sel_attribute == '')
		{
			$data_arr['assigned'] = $data_arr['new'] = $data_arr['parent'] = array();
			$inc_assign = $inc_new = $inc_parent = 0;
			// get all assigned attributes based on the selected category
			$added_attribute_details = $this->getCategoryAttributeDetails($category_id);
		}

		if($sel_attribute != '')
		{
			$attr_details = ProductAttributes::Select('id', 'attribute_question_type', 'validation_rules', 'default_value', 'status', 'is_searchable', 'show_in_list',
												'description', 'attribute_label')->whereRaw('id = ?', array($sel_attribute))->orderBy('id', 'ASC')->get();
		}
		else
		{
			$attr_details = ProductAttributes::Select('id', 'attribute_question_type', 'validation_rules', 'default_value', 'status', 'is_searchable', 'show_in_list',
												'description', 'attribute_label')->orderBy('id', 'ASC')->get();
		}

		if(count($attr_details) > 0)
		{
			foreach($attr_details as $attr)
			{
				$return_row['attribute_id'] = $attr->id;
				$return_row['is_searchable'] = $attr->is_searchable;
				$return_row['show_in_list'] = $attr->show_in_list;
				$return_row['attribute_question_type'] = $attr->attribute_question_type;
				$return_row['attribute_label'] = $attr->attribute_label;
				if(in_array($attr->attribute_question_type, $this->option_fields))
				{
					$attr_options = $this->getAttributeOptions($attr->id);
					$return_row['default_value'] = is_null($attr->default_value) ? '' :$this->getAttributeDefaultOptionValue($attr->default_value);
				}
				else
				{
					$attr_options = array();
					$return_row['default_value'] = is_null($attr->default_value) ? '' :$attr->default_value;
				}
				$return_row['attribute_options'] = $attr_options;
				$return_row['validation_rules'] = is_null($attr->validation_rules) ? '' :$attr->validation_rules ;
				$return_row['status'] = $attr->status;
				$return_row['description'] = $attr->description;

				if($sel_attribute != '')
				{
					return $return_row;
				}

				// check the attribute assigned in parent category
				if(in_array($attr->id, $this->parent_category_attributes))
				{
					$data_arr['parent'][$inc_parent] = $return_row;
					$inc_parent++;
				}
				else if(in_array($attr->id, $added_attribute_details))
				{
					$assigned_arr[$attr->id] = $return_row;
					$inc_assign++;
				}
				else
				{
					$data_arr['new'][$inc_new] = $return_row;
					$inc_new++;
				}
			}

			if($inc_assign)
			{
				$assign_order_arr = array();
				foreach($added_attribute_details as $assign_index => $assign_category_id)
				{
					$assign_order_arr[$assign_index] = $assigned_arr[$assign_category_id];
				}

				$data_arr['assigned'] = $assign_order_arr;
			}
		}
		return $data_arr;
	}

	public function getCategoryAttributeDetails($category_id)
	{
		$current_category_attributes = $parent_category_attributes = array();
		$current_category_id = $category_id;
		// get all parent category ids
		$parent_category_ids = $this->getParentCategoryIds($category_id);
		$parent_category_ids_arr = explode(',', $parent_category_ids);

		$attr_details = \DB::select('SELECT A.attribute_id, A.category_id FROM product_category_attributes AS A, product_attributes AS B WHERE	A.attribute_id = B.id AND (A.category_id IN (' . $parent_category_ids .') OR A.category_id = ? ) ORDER BY A.display_order', array($current_category_id));
		if(count($attr_details) > 0)
		{
			foreach($attr_details as $attr)
			{
				// compare category id of attribute with current category to get parent category attributes
				if($current_category_id != $attr->category_id)
				{
					$parent_category_attributes[] = $attr->attribute_id;
				}
				// check the attribute already assigned in parent category
				else if(!in_array($attr->attribute_id, $parent_category_attributes))
				{
					$current_category_attributes[] = $attr->attribute_id;
				}
			}
		}
		// assign parent category attributes array in object
		//$this->parent_category_attributes = $parent_category_attributes;
		$this->parent_category_attributes = $parent_category_attributes;
		return $current_category_attributes;
	}

	public function getParentCategoryIds($category_id)
	{
		$parent_category_ids = 0;
		$cat_details = \DB::select('select parent.id AS parent_category_id from product_category node, product_category parent where
				node.category_left BETWEEN parent.category_left AND parent.category_right AND node.id = ? AND parent.id != ? AND parent.id != ?
				ORDER BY parent.category_left LIMIT 0, 30', array($category_id, $this->root_category_id, $category_id));
		if (count($cat_details) > 0)
		{
			foreach($cat_details as $cat)
			{
				$parent_category_ids = ($parent_category_ids)?($parent_category_ids . ',' .$cat->parent_category_id ):$cat->parent_category_id;
			}
		}
		return $parent_category_ids;
	}

	public function getAttributeOptions($attribute_id)
	{
		$attribute_options = array();
		$attr_option_details = ProductAttributeOptions::whereRaw('attribute_id = ?', array($attribute_id))->get(array('id', 'option_label', 'is_default_option'));
		if(count($attr_option_details) > 0)
		{
			foreach($attr_option_details as $attr_option)
			{
				$attribute_options[$attr_option->id]['option_label'] = $attr_option->option_label;
				$attribute_options[$attr_option->id]['is_default_option'] = $attr_option->is_default_option;
			}
		}
		return $attribute_options;
	}

	public function getAttributeDefaultOptionValue($attribute_option_id)
	{
		$option_value = ProductAttributeOptions::whereRaw('id = ?', array($attribute_option_id))->pluck('option_label');
		return $option_value;
	}

	public function getCategoryName($category_id)
	{
		$category_name = ProductCategory::whereRaw('id = ?', array($category_id))->pluck('category_name');
		return $category_name;
	}

	public function isAttributeExists($attribute_id)
	{
		$attribute_count = ProductAttributes::whereRaw('id = ?', array($attribute_id))->count();
	    return $attribute_count;
	}

	public function isCategoryExists($category_id)
	{
		$category_count = ProductCategory::whereRaw('id = ?', array($category_id))->count();
	    return $category_count;
	}

	public function isAttributeLabelAlreadyExists($attribute_id, $category_id)
	{
		$category_attr_count = ProductCategoryAttributes::whereRaw('attribute_id = ? AND category_id = ?', array($attribute_id, $category_id))->count();
		return $category_attr_count;
	}

	public function assignAttribute($input_arr)
	{
		if ($this->isAttributeLabelAlreadyExists($input_arr['attribute_id'], $input_arr['category_id']))
		{
			return array(
					'err'=>true,
					'err_msg' => trans('webshoppack::admin/manageCategory.attributes_already_assigned_msg')
				);
		}

		$input_arr['date_added'] = 'Now()';
		$cat_attribute_id = ProductCategoryAttributes::insertGetId($input_arr);

	    $attribute_id = $input_arr['attribute_id'];
	    return array('err' => false, 'err_msg' => '', 'list_row' => $this->getHTMLListRowAssigned($attribute_id, 'assigned', $input_arr['category_id']), 'row_id' => $attribute_id);
	}

	public function getHTMLElement($type, $attr_options, $default_value)
	{
		switch($type)
		{
			case 'text':
				?>
				<input type="text" name="" value="<?php echo $default_value ; ?>" tabindex=-1 class="col-lg-8">
				<?php
				break;
			case 'textarea':
				?>
				<textarea name="" rows="5" cols="35" class="col-lg-8" tabindex=-1><?php echo $default_value ; ?></textarea>
				<?php
				break;
			case 'select':
				?>
				<select name="" tabindex=-1 class="col-lg-8">
					<option value="">-- Select --</option>
					<?php
					foreach($attr_options as $opt_id => $opt_value)
					{
					?>
					<option value="<?php echo $opt_id;?>"<?php if($opt_value['is_default_option'] == 'yes') echo ' selected="selected"'?>><?php echo $opt_value['option_label'];?></option>
					<?php
					}
					?>
				</select>
				<?php
				break;
			case 'option':
				foreach($attr_options as $opt_id => $opt_value)
				{
				?>
				<label class="radio"><input type="radio" class="ace" name="" value="<?php echo $opt_id;?>"<?php if($opt_value['is_default_option'] == 'yes') echo ' checked="checked"'?>>
				<span class="lbl"><?php echo $opt_value['option_label']; ?></span></label>
                <?php
			 	}
				break;
			case 'check':
				foreach($attr_options as $opt_id => $opt_value)
				{
				?>
				<label class="checkbox"><input type="checkbox" class="ace" name="" value="<?php echo $opt_id;?>" tabindex=-1<?php if($opt_value['is_default_option'] == 'yes') echo ' checked="checked"'?>> <span class="lbl"><?php echo $opt_value['option_label'];?></span></label><?php
				}
				break;
			case 'multiselectlist':
				?>
				<select name="" multiple="multiple" tabindex=-1 size="10" class="col-lg-8">
					<option value="">-- Select --</option>
					<?php
					foreach($attr_options as $opt_id => $opt_value)
					{
					?>
					<option value="<?php echo $opt_id;?>"<?php if($opt_value['is_default_option'] == 'yes') echo ' selected="selected"'?>><?php echo $opt_value['option_label'];?></option>
					<?php
					}
					?>
				</select>
				<?php
				break;
			default:
				die('Error: Developer, Configuration file update, needs to be reflected in fn:getHTMLElement , ' . $type);
				?>
				<input type="text" name=""  class="col-lg-4" value="<?php echo $type;?>" tabindex=-1>
				<?php
		}
	}

	public function getHTMLListRowAssigned($row_id, $sel_option = 'assigned', $category_id)
	{
		$attr_details = ProductAttributes::Select('id', 'attribute_label', 'attribute_question_type', 'default_value', 'validation_rules', 'status')->whereRaw('id = ?', array($row_id))->first();
		if(count($attr_details) > 0)
		{
			ob_start();
			if(in_array($attr_details['attribute_question_type'], $this->option_fields))
			{
				$attr_options = $this->getAttributeOptions($attr_details['id']);
				$row['default_value'] = is_null($attr_details['default_value']) ? '' :$this->getAttributeDefaultOptionValue($attr_details['default_value']);
			}
			else
			{
				$attr_options = array();
			}
			if($sel_option == 'assigned')
			{
			?>
			<tr id="formBuilderRow_<?php echo $attr_details['id'];?>" class="formBuilderRow formAssignedAttributes">
			<?php
			}
			else
			{
			?>
			<tr id="formBuilderNewRow_<?php echo $attr_details['id'];?>" class="nodrag nodrop formBuilderAddRow formUnassignedAttributes" title="<?php echo trans('webshoppack::admin/manageCategory.double_click_assign_attributes_msg'); ?>">
			<?php
			}
			?>
					<td><?php echo $attr_details['attribute_label'];?></td>
			        <td><?php echo $this->getHTMLElement($attr_details['attribute_question_type'], $attr_options, $attr_details['default_value']);?></td>
			        <td class="formBuilderAction clsUnasinedAtributes">
			        <?php
			        if($sel_option == 'assigned')
			        {
			        ?>
			        <a class="formBuilderRowDelete" onclick="javascript:formBuilderRemoveListRow(<?php echo $attr_details['id'];?>, <?php echo $category_id;?>);" style="display: none;" href="javascript: void(0);" title="<?php echo trans('webshoppack::admin/manageCategory.remove_attribute'); ?> "><i class="icon-trash bigger-130"></i> </a>&nbsp;
			        <?php
			        }
			        else
			        {
					?>
					<a class="formBuilderRowEdit" onclick="javascript:formBuilderAddListRow(<?php echo $attr_details['id'];?>, <?php echo $category_id;?>);" style="display: none;" href="javascript: void(0);" title="<?php echo trans('webshoppack::admin/manageCategory.assign_attribute_title'); ?> "><i class="icon-share bigger-130"></i> </a>&nbsp;
					<?php
					}
					?>
					<a class="formBuilderRowView" style="display: none;" href="<?php echo \URL::action('Agriya\Webshoppack\AdminCategoryAttributesController@getViewAttribute') ?>?attribute_id=<?php echo $attr_details['id'];?>" title="<?php echo trans('webshoppack::admin/manageCategory.view_attribute') ?> " id="formBuilderRowView_<?php echo $attr_details['id'];?>"><i class="icon-eye-open bigger-130"></i> </a>
			        </td>
			        </tr>
			<?php
			$content = ob_get_clean();
			return $content;
		}
	}

	public function removeAttribute($category_id, $attribute_id)
	{
		$affectedRows = ProductCategoryAttributes::whereRaw('attribute_id = ? AND category_id = ?', array($attribute_id, $category_id))->delete();
		if($affectedRows)
		{
			return array('err' => false, 'err_msg' => '', 'list_row' => $this->getHTMLListRowAssigned($attribute_id, 'removed', $category_id), 'row_id'=>$attribute_id);
		}
		else
		{
			return array('err' => true, 'err_msg' => trans('webshoppack::admin/manageCategory.attributes_removed__err_msg'));
		}
	}

	public function updateListRowOrder($input_arr)
	{
		foreach($input_arr['attrdnd'] as $display_order => $attribute_id_str)
		{
			$temp = explode("_", $attribute_id_str);
			$attribute_id = (isset($temp[1]) && $temp[1]) ? (int) $temp[1] : false;
			$category_id = $input_arr['category_id'];
			if($attribute_id)
			{
				$data_arr['display_order'] = $display_order;
				ProductCategoryAttributes::whereRaw('attribute_id = ? AND category_id = ?', array($attribute_id, $category_id))->update($data_arr);
			}
		}
	}
}