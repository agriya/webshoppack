<?php namespace Agriya\Webshoppack;

class AdminProductAttributesService extends AdminCategoryAttributesService
{
	public $option_fields = array('select', 'check', 'option', 'multiselectlist');

	public function buildProductAttributesQuery()
	{
		$this->qry = ProductAttributes::Select('id', 'attribute_question_type', 'validation_rules', 'default_value', 'status', 'attribute_label');
		$this->qry->orderBy('id', 'asc');
		return $this->qry;
	}

	public function getValidatorRule($field)
	{
		$rules['attribute_label'] = 'Required';
		$rules['attribute_question_type'] = 'Required';
		return isset($rules[$field])? $rules[$field] : '';
	}

	public function addAttribute($input_arr)
	{
		$validation_rules = '';
		if(!empty($input_arr['validation_rules']))
		{
			$validation_rules = $this->processValidationRules($input_arr['validation_rules']);
		}
		$data_arr['attribute_label'] = $input_arr['attribute_label'];
		$data_arr['description'] = $input_arr['description'];
		$data_arr['date_added'] = \DB::raw('NOW()');
		$data_arr['validation_rules'] = $validation_rules;
		$data_arr['default_value'] = $input_arr['attribute_default_value'];
		$data_arr['is_searchable'] = $input_arr['attribute_is_searchable'];

		$attribute_id = ProductAttributes::insertGetId($data_arr);
		if (!empty($input_arr['attribute_options']) && sizeof($input_arr['attribute_options']) > 0)
		{
	    	$this->addAttributeOptions($attribute_id, $input_arr['attribute_options'], $input_arr['attribute_default_value']);
	    }
	    return array('err' => false, 'err_msg' => '', 'list_row' => $this->getHTMLListRow($attribute_id));
	}

	public function processValidationRules($validation_rules)
	{
		if(!empty($validation_rules) && is_array($validation_rules))
		{
			foreach($validation_rules as &$validation_rule)
			{
				if(\Input::get($validation_rule.'_input') != "")
				{
					$validation_rule .= '-' . \Input::get($validation_rule.'_input');
				}
			}
			return implode('|',$validation_rules);
		}
		return '';
	}

	public function addAttributeOptions($attribute_id, $attribute_options, $attribute_default_value)
	{
		if(empty($attribute_options))
			return false;

	    for($i = 0 ; $i < sizeof($attribute_options) ; $i++)
		{
			if(empty($attribute_options[$i])) continue;

			$is_default_option = (strcmp($attribute_options[$i], $attribute_default_value) == 0) ?  'yes': 'no';

			$data_arr['attribute_id'] = $attribute_id;
			$data_arr['option_label'] = $attribute_options[$i];
			$data_arr['option_value'] = $attribute_options[$i];
			$data_arr['is_default_option'] = $is_default_option;

			$attribute_option_id = ProductAttributeOptions::insertGetId($data_arr);

	        // if default option saved then update this id in attribute default value
	        if($is_default_option == 'yes')
	        {
				$this->setAttributeDefaultOptionId($attribute_id, $attribute_option_id);
			}
	    }
	    return true;
	}

	public function setAttributeDefaultOptionId($attribute_id, $attribute_option_id)
	{
		$data_arr['default_value'] = $attribute_option_id;
		ProductAttributes::whereRaw('id = ?', array($attribute_id))->update($data_arr);
	}

	public function getHTMLListRow($row_id)
	{
		$attr_details = ProductAttributes::Select('id', 'attribute_label', 'attribute_question_type', 'default_value', 'validation_rules', 'status')->whereRaw('id = ?', array($row_id))->first();
		if(count($attr_details) > 0)
		{
			ob_start();
			if(in_array($attr_details['attribute_question_type'], $this->option_fields))
			{
				$attr_options = $this->getAttributeOptions($attr_details['id']);
				$attr_details['default_value'] = is_null($attr_details['default_value']) ? '' : $this->getAttributeDefaultOptionValue($attr_details['default_value']);
			}
			else
			{
				$attr_options = array();
			}
			?>
			<tr id="formBuilderRow_<?php echo $attr_details['id'];?>" class="formBuilderRow">
				<td><?php echo $attr_details['attribute_label'];?></td>
		        <td><?php echo $this->getHTMLElement($attr_details['attribute_question_type'], $attr_options, $attr_details['default_value']);?></td>
		        <td><?php echo $attr_details['validation_rules'];?></td>
		        <td><?php echo $attr_details['default_value'];?></td>
		        <td>
                	<?php
						$lbl_class = "";
						if(strtolower ($attr_details['status']) == "active")
							$lbl_class = "label-success";
						elseif(strtolower ($attr_details['status']) == "inactive")
							$lbl_class = "label-grey arrowed-in arrowed-in-right";
					?>
                	<span class="label <?php echo $lbl_class ;?>"><?php echo $attr_details['status'];?></span>
                </td>
		        <td class="formBuilderAction">
                    <a class="formBuilderRowDelete btn btn-danger btn-xs" onclick="javascript:formBuilderRemoveListRow(<?php echo $attr_details['id'];?>);" style="display: none;" href="javascript: void(0);" title="<?php echo trans('webshoppack::admin/manageCategory.remove_attribute'); ?>"><i class="icon-trash bigger-130"></i> </a> &nbsp;
                    <a class="formBuilderRowEdit btn btn-info btn-xs" onclick="javascript:formBuilderEditListRow(<?php echo $attr_details['id'];?>);" style="display: none;" href="javascript: void(0);" title="<?php echo trans('webshoppack::admin/manageCategory.edit_attribute'); ?>"><i class="icon-pencil bigger-130"></i> </a>
		        </td>
	        </tr>
			<?php
			$content = ob_get_clean();
			return $content;
		}
	}

	public function getListRow($row_id)
	{
		$attr_details = ProductAttributes::Select('id', 'attribute_label', 'attribute_question_type', 'default_value', 'validation_rules', 'status', 'description')->whereRaw('id = ?', array($row_id))->first();
		if(count($attr_details) > 0)
		{
			$attributes['attribute_id'] = $attr_details['id'];
	    	$attributes['attribute_label'] = $attr_details['attribute_label'];
	    	$attributes['default_value'] = $attr_details['default_value'];
	    	$attributes['validation_rules'] = $attr_details['validation_rules'];
	    	$attributes['attribute_question_type'] = $attr_details['attribute_question_type'];
	    	$attributes['attribute_is_searchable'] = $attr_details['is_searchable'];
	    	$attributes['attribute_status'] = $attr_details['status'];
	    	$attributes['description'] = $attr_details['description'];

			if(in_array($attr_details['attribute_question_type'], $this->option_fields))
			{
	    		$attributes['options'] = $this->getListChildRows($attr_details['id']);
	    		$attributes['options_size'] = sizeof($attributes['options']);
	        	$attributes['options_used'] = $this->isOptionsAlreadyUsed($attr_details['id']);
	    	}else
			{
				$attributes['options_size'] = 0;
	    		$attributes['options_used'] = false; // just to make sure that this field exists to check condition
			}
			return $attributes;
		}
		return false;
	}

	public function getListChildRows($row_id)
	{
		$attr_option_details = ProductAttributeOptions::Select('id', 'is_default_option', 'option_label')->whereRaw('attribute_id = ?', array($row_id))->get();
		$attrib = array();
		$i = 0;
		if(count($attr_option_details) > 0)
		{
			foreach($attr_option_details as $attr_key => $attr)
			{
				$attrib[$i]['id'] = $attr['id'];
				$attrib[$i]['option_label'] = $attr['option_label'];
				$attrib[$i]['is_default_option'] = $attr['is_default_option'];
				$i++;
			}
			return $attrib;
		}
		return $attrib;
	}

	public function isOptionsAlreadyUsed($attribute_id)
	{
		$attribute_used = false;

		// check attribute options used in items
		$attr_option_count = ProductAttributesOptionValues::whereRaw('attribute_id = ?', array($attribute_id))->count();
		if($attr_option_count > 0)
		{
			$attribute_used = true;
		}
		else
		{
			// check attributes without options like textbox/textarea used in items
			$attr_values_count = ProductAttributesValues::whereRaw('attribute_id = ?', array($attribute_id))->count();
			if($attr_values_count > 0)
			{
				$attribute_used = true;
			}
		}
		return $attribute_used;
	}

	public function deleteListRowChild($parent_row_id)
	{
		# Get all attribute option ids related to the deleted attribute
		$attr_option_details = ProductAttributeOptions::Select('id')->whereRaw('attribute_id = ?', array($parent_row_id))->get();
		if(count($attr_option_details) > 0)
		{
			foreach($attr_option_details as $attr_option)
			{
				# Delete attribute options
		    	$this->deleteAttributeOption($parent_row_id, $attr_option['id']);
			}
		}
		return true;
	}

	public function deleteAttributeOption($attribute_id, $attribute_option_id)
	{
		ProductAttributeOptions::whereRaw('id = ? AND attribute_id = ?', array($attribute_option_id, $attribute_id))->delete();
		return true;
	}

	public function updateListRow($row_id, $data_arr)
	{
		$attributes = $this->getListRow($row_id);

		// update attributes if the attribute type didn't change
		if($attributes['attribute_question_type'] != $data_arr['attribute_question_type'])
		{
			if($this->isOptionsAlreadyUsed($row_id) &&
				((in_array($data_arr['attribute_question_type'], array_keys(\Config::get('webshoppack::ui_no_options'))) && in_array($attributes['attribute_question_type'], array_keys(\Config::get('webshoppack::ui_options')))) ||
				(in_array($attributes['attribute_question_type'], array_keys(\Config::get('webshoppack::ui_no_options'))) && in_array($data_arr['attribute_question_type'], array_keys(\Config::get('webshoppack::ui_options'))))))
			{
				// Check attribute is used or not
				return array('err' => true, 'err_msg' => trans('webshoppack::admin/manageCategory.add-attribute.attribute_option_in_use'));
			}
			else
			{
				if( in_array($data_arr['attribute_question_type'], array_keys(\Config::get('webshoppack::ui_no_options'))) &&
					 in_array($attributes['attribute_question_type'], array_keys(\Config::get('webshoppack::ui_no_options'))))
				{
					# Remove all options for this attribute.
					$this->deleteListRowChild($row_id);
				}
			}
		}

		# Remove all options for this attribute.
		$this->updateAttributes($row_id, $data_arr);

		// check if this attribute type has options? and if available update them.
		$ui_elements_options =  array_keys(\Config::get('webshoppack::ui_options'));
		$unremoved_options_count = 0;
		if(in_array($data_arr['attribute_question_type'], $ui_elements_options)
			|| in_array($attributes['attribute_question_type'], $ui_elements_options))
		{
			// options available
			// update any existing options changes
			$removed_options = array();
			for($i = 0 ; $i < $attributes['options_size']; $i++)
			{
				$attribute_options_ids = $attributes['options'][$i]['id'];
				if(isset($data_arr['attribute_options_' . $attribute_options_ids]) && !empty($data_arr['attribute_options_' . $attribute_options_ids]))
				{
					//$this->setFormField('attribute_options_' . $attribute_options_ids, $_REQUEST['attribute_options_' . $attribute_options_ids]);
					//Update Product attribute options
					$is_default_option = (strcmp($data_arr['attribute_options_'.$attribute_options_ids], $data_arr['attribute_default_value']) == 0) ? 'yes': 'no';
					$this->updateAttributesOptions($row_id, $data_arr, $attribute_options_ids, $is_default_option);

					// if default option saved then update this id in attribute default value
			        if($is_default_option == 'yes')
			        {
						$this->setAttributeDefaultOptionId($row_id, $attribute_options_ids);
					}
				}
				else
				{
					$removed_options[] = $attribute_options_ids;
				}
			}

			// Add any new options
			if (!empty($data_arr['attribute_options']) && sizeof($data_arr['attribute_options']) > 0)
			{
				$this->addAttributeOptions($row_id, $data_arr['attribute_options'], $data_arr['attribute_default_value']);
			}

			// Remove existing options which are removed by user
			$removed_options_size = sizeof($removed_options);
			if($removed_options_size)
			{
				for($j = 0 ; $j < $removed_options_size ; $j++)
				{
					if(!$this->isAttributeOptionsUsed($removed_options[$j]))
					{
						# Remove all details for the selected attribute option.
						$this->deleteAttributeOption($row_id, $removed_options[$j]);
					}
					else
					{
						$unremoved_options_count++;
					}
		        }
			}
		}

		return array(
			'err'=>false,
			'err_msg'=>'',
			'list_row'=>$this->getHTMLListRow($row_id),
			'unremoved_options_count'=>$unremoved_options_count
		);
	}

	public function updateAttributes($attribute_id, $input_arr)
	{
		$validation_rules = '';
		if(!empty($input_arr['validation_rules']))
		{
			$validation_rules = $this->processValidationRules($input_arr['validation_rules']);
		}
		$data_arr['attribute_label'] = $input_arr['attribute_label'];
		$data_arr['attribute_question_type'] = $input_arr['attribute_question_type'];
		$data_arr['default_value'] = $input_arr['attribute_default_value'];
		$data_arr['validation_rules'] = $validation_rules;
		$data_arr['is_searchable'] = $input_arr['attribute_is_searchable'];
		$data_arr['status'] = $input_arr['status'];
		$data_arr['description'] = $input_arr['description'];
		ProductAttributes::whereRaw('id = ?', array($attribute_id))->update($data_arr);
	}

	public function updateAttributesOptions($row_id, $input_arr, $attribute_options_ids, $is_default_option)
	{
		$data_arr['option_value'] = $input_arr['attribute_options_'.$attribute_options_ids];
		$data_arr['is_default_option'] = $is_default_option;
		ProductAttributeOptions::whereRaw('id = ?', array($attribute_options_ids))->update($data_arr);
	}

	public function isAttributeOptionsUsed($attribute_option_id)
	{
		$option_used = false;

		// check attribute options used in items
		$attr_option_count = ProductAttributesOptionValues::whereRaw('attribute_options_id = ?', array($attribute_option_id))->count();
		if($attr_option_count > 0)
		{
			$option_used = true;
		}

		return $option_used;
	}

	public function deleteListRow($row_id)
	{
		ProductAttributes::whereRaw('id = ?', array($row_id))->delete();
		# Delete all attribute options related to the deleted attribute
		$this->deleteListRowChild($row_id);
		return true;
	}
}