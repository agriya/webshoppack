@extends(Config::get('webshoppack::package_admin_layout'))
@section('content')
	<div id="catalogLoadingImageDialog" title="" style="display:none;">
		<p style="align:center;text-align:center;">
	        <img src="{{ URL::asset('packages/agriya/webshoppack/images/general/loader.gif') }}" alt="loading" />
		</p>
	</div>

	<div id="dialog-delete-confirm" title="" style="display:none;">
	    <span class="ui-icon ui-icon-alert"></span>
		<span class="show ml15">{{ trans('webshoppack::admin/manageCategory.delete-attribute.delete_attribute_confirm') }}</span>
	</div>
	<div id="dialog-err-msg" title="" style="display:none;">
	    <span class="ui-icon ui-icon-alert"></span>
		<span id="dialog-err-msg-content" class="show ml15"></span>
	</div>
	{{ Form::open(array('id'=>'attributeListfrm', 'method'=>'get','class' => 'form-horizontal form-request' )) }}
    	<div class="message-navbar mb20 mt10">
        	<h1 class="admin-title blue bigger-150">{{ trans('webshoppack::admin/manageCategory.product_attribute_title') }}</h1>
        </div>
        <div class="alert alert-info">
			<strong>{{ trans('webshoppack::common.note') }}:</strong> {{ trans('webshoppack::admin/manageCategory.list-attribute.list_attribute_note') }}
		</div>
		<table id="sample-table-1" class="table table-striped table-bordered table-hover">
			<thead class="thin-border-bottom">
				<tr>
					<th class="col-sm-2">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_label') }}</th>
					<th class="col-sm-3">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_type') }}</th>
					<th class="col-sm-3">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_validation') }}</th>
					<th>{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_default_value') }}</th>
					<th>{{ trans("webshoppack::common.status") }}</th>
					<th class="col-sm-2">{{ trans("webshoppack::common.action") }}</th>
				</tr>
			</thead>
			<tbody class="formBuilderListBody">
				@if(count($attribute_details) > 0)
					@foreach($attribute_details as $attributeKey => $attribute)
						<tr id="formBuilderRow_{{ $attribute['id'] }}" class="formBuilderRow">
							<td>{{ $attribute['attribute_label'] }}</td>
								<?php
									if(in_array($attribute['attribute_question_type'], $options))
									{
										$attr_options = $prod_attr_service_obj->getAttributeOptions($attribute['id']);
										$default_value = is_null($attribute['default_value']) ? '' :$prod_attr_service_obj->getAttributeDefaultOptionValue($attribute['default_value']);
									}
									else
									{
										$attr_options = array();
										$default_value = is_null($attribute['default_value']) ? '' :$attribute['default_value'];
									}
								?>
							<td class="multi-select">
								<div class="radio-list check-list">
									{{ $prod_attr_service_obj->getHTMLElement($attribute['attribute_question_type'], $attr_options, $attribute['default_value']) }}
								</div>
							</td>
							<td>{{ $attribute['validation_rules'] }}</td>
							<td>{{ $default_value }}</td>
							<td>
								<?php
									$lbl_class = "";
									if(strtolower ($attribute['status']) == "active")
										$lbl_class = "label-success";
									elseif(strtolower ($attribute['status']) == "inactive")
										$lbl_class = "label-info arrowed-in arrowed-in-right";
								?>
								<span class="label {{ $lbl_class }}">{{ $attribute['status'] }}</span>
							</td>
							<td class="formBuilderAction hidden-sm btn-group">
								<a class="formBuilderRowDelete btn btn-danger btn-xs" onclick="javascript:formBuilderRemoveListRow({{$attribute['id']}});" style="display: none;" href="javascript: void(0);" title="{{ trans('webshoppack::admin/manageCategory.remove_attribute') }}"><i class="icon-trash bigger-120"></i> </a> &nbsp;
								<a class="formBuilderRowEdit btn btn-info btn-xs" onclick="javascript:formBuilderEditListRow({{$attribute['id']}});" style="display: none;" href="javascript: void(0);" title="{{ trans('webshoppack::admin/manageCategory.edit_attribute') }}"><i class="icon-edit bigger-120"></i> </a>
							</td>
						</tr>
					@endforeach
			   @else
					<tr><td colspan="9"><p class="alert alert-info">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_not_found') }}</p></td></tr>
			   @endif
			</tbody>
		</table>
		@if(count($attribute_details) > 0)
			<div class="text-right">{{ $attribute_details->links() }}</div>
		@endif
    {{ Form::close() }}

    <section>
		<h2 class="title-two" onclick="showAddForm(0);"><span class="btn-link">{{ trans('webshoppack::admin/manageCategory.add_attribute') }} <i class="fa fa-angle-down"></i></span></h2>
		<div id="add_attributes" style="display:none;">
			{{ Form::open(array('class' => 'form-horizontal',  'id' => 'addAttributefrm', 'name' => 'addAttributefrm')) }}
				 <div id="ajaxMsgs" class="alert alert-danger" style="display:none;"></div>
				 <div id="ajaxMsgSuccess" class="alert alert-success" style="display:none;"></div>
				 {{ Form::hidden('attribute_action', 'add_attribute', array("id" => "attribute_action")) }}
				 {{ Form::hidden('attribute_id', '', array("id" => "attribute_id")) }}
				 {{ Form::hidden('attribute_options_count', '', array("id" => "attribute_options_count")) }}
				<div class="border-type1 search-bar">
					<h4 class="title-seven mb30">{{ trans('webshoppack::admin/manageCategory.add_edit_attribute') }}</h4>
					<div class="form-group {{{ $errors->has('attribute_label') ? 'error' : '' }}}">
						{{ Form::label('attribute_label', trans('webshoppack::admin/manageCategory.list-attribute.attribute_label'), array('class' => 'col-sm-2 control-label required-icon')) }}
						<div class="col-sm-5">
							{{  Form::text('attribute_label', null, array('class' => 'col-xs-10 col-sm-9 valid')); }}
							<label class="error">{{{ $errors->first('attribute_label') }}}</label>
						</div>
					</div>

					<div class="form-group {{{ $errors->has('description') ? 'error' : '' }}}">
						{{ Form::label('description', trans('webshoppack::admin/manageCategory.add-attribute.attribute_description'), array('class' => 'col-sm-2 control-label')) }}
						<div class="col-sm-5">
							{{  Form::text('description', null, array('class' => 'col-xs-10 col-sm-9 valid')); }}
							<label class="error">{{{ $errors->first('description') }}}</label>
						</div>
					</div>

					<div class="form-group {{{ $errors->has('attribute_question_type') ? 'error' : '' }}}">
						{{ Form::label('attribute_question_type', trans('webshoppack::admin/manageCategory.list-attribute.attribute_type'), array('class' => 'col-sm-2 control-label required-icon')) }}
						<div class="col-sm-5">
							<div id="attribute_options_already_used" style="display:none;" class="cannotAccessMsg">
							{{ trans('webshoppack::admin/manageCategory.add-attribute.attribute_option_in_use') }}</div>
							{{ Form::select('attribute_question_type',  $ui_elements_all, '', array('class' => 'col-xs-10 col-sm-9 valid control-label required-icon', 'onchange' => 'showOptions()')) }}
							<p class="clearfix"><label class="error">{{{ $errors->first('attribute_question_type') }}}</label></p>
							<div id="options_ctrls" style="display:none;">
								<p class="text-muted">{{ trans('webshoppack::admin/manageCategory.add-attribute.attribute_options_title') }}</p>
								<ul id="attribute_options_group_ul" class="list-unstyled">
									<li id="attribute_options_group_ul_li" class="form-group">
										{{  Form::text('attribute_options[]', null, array('class' => 'col-xs-10 col-sm-9')); }}&nbsp;
										<a href="javascript: void(0);" onclick="addClone();" title="{{ trans('webshoppack::admin/manageCategory.add_option') }}" class="clsAddOption green">
										<i class="fa fa-plus"></i>+</a>&nbsp;
										<a href="javascript: void(0);" onclick="removeClone(this);" title="{{ trans('webshoppack::admin/manageCategory.remove_option') }}" class="clsRemoveOption red">
										<i class="fa fa-minus"></i>-</a>&nbsp;
										<a class="setAttributeOption clsSetDefaultOption" href="javascript: void(0);" onclick="setSelected(this);" title="{{ trans('webshoppack::admin/manageCategory.select_by_default') }}" ><strong>S</strong></a>
									</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="form-group {{{ $errors->has('attribute_default_value') ? 'error' : '' }}}" id="default_value_row">
						{{ Form::label('attribute_default_value', trans('webshoppack::admin/manageCategory.list-attribute.attribute_default_value'), array('class' => 'col-sm-2 control-label')) }}
						<div class="col-sm-5">
							{{  Form::text('attribute_default_value', null, array('class' => 'col-xs-10 col-sm-9 valid')); }}
							<label class="error">{{{ $errors->first('attribute_default_value') }}}</label>
						</div>
					</div>

					<div class="form-group {{{ $errors->has('validation_rules') ? 'error' : '' }}}">
						{{ Form::label('validation_rules', trans('webshoppack::admin/manageCategory.list-attribute.attribute_validation'), array('class' => 'col-sm-2 control-label')) }}
						<div class="col-sm-5 check-list">
							@if(count(Config::get('webshoppack::validation_rules')) > 0)
								@foreach(Config::get('webshoppack::validation_rules') as $key => $val)
									<div class="checkbox">
										<label>
											{{ Form::checkbox('validation_rules[]', $val['name'], false, array("id" => $val['name'], "class" => "clsValidationRules ace")) }}
											<span class="lbl">{{ Form::label($val['name'], $val['caption']) }}</span>
										</label>
									</div>
								@endforeach
								<ul class="clsValidationRulesInputBoxesList list-unstyled">
									@foreach(Config::get('webshoppack::validation_rules') as $key => $val)
										@if($val['input_box'])
											<li class="{{ $val['name'] }}ListItem clsValidationRulesInputBoxesListItem mt10 clearfix" style="display:none;">
												{{ Form::label($val['name'].'_input', $val['caption']) }}
												{{  Form::text($val['name'].'_input', null, array('id' => $val['name'].'_input', 'class' => 'col-xs-10 col-sm-9 valid clsValidationRulesInputBox')); }}
											</li>
										@endif
									@endforeach
								</ul>
							@endif
							<label class="error">{{{ $errors->first('validation_rules') }}}</label>
						</div>
					</div>

					<div class="form-group {{{ $errors->has('attribute_is_searchable') ? 'error' : '' }}}">
						{{ Form::label('attribute_is_searchable', trans('webshoppack::admin/manageCategory.add-attribute.attribute_is_searchable'), array('class' => 'col-sm-2 control-label')) }}
						<ul class="col-sm-7 list-unstyled radio-list">
							<li class="radio-inline radio">
								<label>
									{{ Form::radio('attribute_is_searchable', 'yes', ($d_arr['attribute_is_searchable'] == 'yes') ? true : false, array('id' => 'attribute_is_searchable_yes', 'name' => 'attribute_is_searchable', 'class' => 'ace')) }}
									<span class="lbl">{{ Form::label('attribute_is_searchable_yes', trans('webshoppack::common.yes'))}}</span>
								</label>
							</li>
							<li class="radio-inline radio">
								<label>
									{{ Form::radio('attribute_is_searchable', 'no', ($d_arr['attribute_is_searchable'] == 'no') ? true : false, array('id' => 'attribute_is_searchable_no', 'name' => 'attribute_is_searchable', 'class' => 'ace')) }}
									<span class="lbl">{{ Form::label('attribute_is_searchable_no', trans('webshoppack::common.no'))}}</span>
								</label>
							</li>
							<label class="error">{{{ $errors->first('attribute_is_searchable') }}}</label>
						</ul>
					</div>

					<div class="form-group {{{ $errors->has('status') ? 'error' : '' }}}">
						{{ Form::label('status', trans('webshoppack::common.status'), array('class' => 'col-sm-2 control-label required-icon')) }}
						<ul class="col-sm-7 list-unstyled radio-list">
							<li class="radio-inline radio">
								<label>
									{{ Form::radio('status', 'active', ($d_arr['status'] == 'active') ? true : false, array('id' => 'status_active', 'name' => 'status', 'class' => 'ace')) }}
									<span class="lbl">{{ Form::label('status_active', trans('webshoppack::common.active'))}}</span>
								</label>
							</li>
							<li class="radio-inline radio">
								<label>
									{{ Form::radio('status', 'inactive', ($d_arr['status'] == 'inactive') ? true : false, array('id' => 'status_inactive', 'name' => 'status', 'class' => 'ace')) }}
									<span class="lbl">{{ Form::label('status_inactive', trans('webshoppack::common.inactive'))}}</span>
								</label>
							</li>
							<label class="error">{{{ $errors->first('status') }}}</label>
						</ul>
					</div>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-5">
							<button type="submit" name="attributes_add_submit" class="btn btn-info btn-sm">
							<i class="icon-ok bigger-110"></i>{{ trans('webshoppack::admin/manageCategory.save_attribute') }}</button>
							<button type="button" name="attributes_cancel" value="cancel_script" class="btn btn-sm" onclick="javascript:location.href='{{ Url::to('admin/product-attributes') }}'"><i class="icon-remove bigger-110"></i> {{ trans("webshoppack::common.cancel")}}</button>
						</div>
					</div>
				</div>
			{{ Form::close() }}
		</div>
    </section>
@stop
@section('script_content')
	<script src="{{ URL::asset('packages/agriya/webshoppack/js/lib/jQuery_plugins/jquery.tablednd_0_5.js') }}"></script>
	<script type="text/javascript">
		/** Show edit & delete icons **/
		$(document).ready(function()
		{
			initializeEditDelete();
		});

		/** Enables the row to respond to user by highlighting and showing edit/delete buttons **/
		function initializeEditDelete()
		{
			 $('.formBuilderRow').mouseover(
				function()
				{
					$(this).addClass('formBuilderMouseover');
					$(this).children('.formBuilderAction').children('.formBuilderRowDelete').show();
					$(this).children('.formBuilderAction').children('.formBuilderRowEdit').show();
				}
			).mouseout(
				function()
				{
					$(this).removeClass('formBuilderMouseover');
					$(this).children('.formBuilderAction').children('.formBuilderRowDelete').hide();
					$(this).children('.formBuilderAction').children('.formBuilderRowEdit').hide();
				}
			);
		}
		var attribute_options_edit_name = 'attribute_options_';
		function showAddForm(row_id)
		{
			$('html,body').animate({scrollTop: $("#addAttributefrm").offset().top}, 300);
			resetAddForm();
			if (row_id > 0)
			{
				$('#add_attributes').show();
				$('#attribute_action').val('update_attribute');
			}
			else
			{
				$('#add_attributes').hide(); // only show difference that form changes between add and edit;
				$('#add_attributes').show();
				$('#attribute_action').val('add_attribute');
			}
		}

		function showOptions()
		{
			if (isOptionUIElement($('#attribute_question_type').val()))
			{
				$('#options_ctrls').css('display','block');
				$('#default_value_row').css('display','none');
				changeValidationFields(false);
				return true;
			}
			else
			{
				$('#options_ctrls').css('display','none');
				$('#default_value_row').css('display','block');
				removeOptions();
				changeValidationFields(true);
				return false;
			}
		}

		function isOptionUIElement(ui_element)
		{
			// UI User Interface
			<?php
			$options = \Config::get('webshoppack::ui_options');
			if (is_array($options) && !empty($options))
			{
				$ui_elements = "var ui_elements = Array('" . implode("', '", array_keys($options)) . "');\n";
				echo $ui_elements; // javascript generation
			}
			else
			{
				echo "var ui_elements = Array();\n";
			}
			?>
			if (!ui_element)  return false;

			for ( i = 0 ; i < ui_elements.length ; i++)
			{
				if (ui_elements[i] == ui_element)
					return true;
			}

			return false;
		}

		var changeValidationFields = function()
		{
			var enable_option = arguments[0];
			var change_fields_arr = Array('numeric', 'alpha', 'maxlength', 'minlength');
			var fields_length = change_fields_arr.length;

			for ( i = 0 ; i < fields_length ; i++)
			{
				// enable validations
				if(enable_option)
				{
					$('#'+change_fields_arr[i]).removeAttr('disabled');
					if($('#'+change_fields_arr[i]).attr('checked'))
					{
						$('.'+change_fields_arr[i]+'ListItem').show();
						$('#'+change_fields_arr[i]+'_input').removeAttr('disabled');
					}
				}
				// disable validations
				else
				{
					$('#'+change_fields_arr[i]).attr('checked', false).attr('disabled', true);
					$('.'+change_fields_arr[i]+'ListItem').hide();
					$('#'+change_fields_arr[i]+'_input').attr('disabled', true);
				}
			}
		}

		/** Remove the attribute option as the default value. **/
		function removeSelected(elem)
		{
			$(elem).css('font-weight','');
		}

		function resetAddForm()
		{
			document.getElementById('addAttributefrm').reset(); // reset form
			removeOptions(); // if more than 1 option present remove them..
			showOptions(); // while in edit attrib, the form.reset doesn't hide options txtbx(s)
			$('#ajaxMsgs').hide('{{ trans('webshoppack::admin/manageCategory.add-attribute.attribute_err_add') }}');
			$('.clsValidationRulesInputBoxesListItem').hide();
			$('.clsValidationRulesInputBox').attr('disabled','disabled');

			// Remove attribute type disable option by default
			$('#attribute_options_already_used').hide();
			$('#attribute_question_type').removeAttr('disabled');
		}

		function removeOptions()
		{
			// one parent should be present for cloning.. if no parent present make the first child as parent and remove child(s)
			if($('#attribute_options_group_ul li').size('.child') == $('#attribute_options_group_ul li').size())
			{
				// removing child class so that  the first element doesn't get removed
				$('#attribute_options_group_ul li').first().removeClass('child');
			}

			var obj = $('#attribute_options_group_ul li').remove('.child');
			$('#attribute_options_group_ul li').children().first('input').val('');
			$('#attribute_options_group_ul li').children().first('input').attr('name','attribute_options[]');
			$('#attribute_default_value').val(''); // reset default value
			$('#attribute_options_count').val($('#attribute_options_group_ul li').size());
		}

		/** Used to display corresponding list items & text box based on checkbox checked value **/
		$('.clsValidationRules').click(function()
		{
			if ($(this).prop('checked'))
			{
				$('.' + $(this).val() + 'ListItem').show();
				$('#' + $(this).val() + '_input').removeAttr('disabled');
				//$('#' + $(this).val() + '_input').addClass('required');
			}else
			{
				$('.' + $(this).val() + 'ListItem').hide();
				$('#' + $(this).val() + '_input').attr('disabled','disabled');
				//$('#' + $(this).val() + '_input').removeClass('required');
			}
		});

		var addClone = function()
		{
			var txt_val = arguments[0];
			var options_id = arguments[1];

			if (!txt_val) txt_val = '';
			var clone = $('#attribute_options_group_ul li').last().clone(); // clone the last node

			if (options_id)
			{
				clone.children('input').attr('name', attribute_options_edit_name + options_id);
			}
			else
			{
				clone.children('input').attr('name','attribute_options[]');
			}
			// Set tabindex value
			var cur_tabindex = parseInt(clone.children('input').attr('tabindex'));
			clone.children('input').attr('tabindex', cur_tabindex);
			$('#attributes_add_submit').attr('tabindex', cur_tabindex + 5);

			clone.children('input').val(txt_val); // set value to empty

			clone.addClass('child'); // add a class so that these can be removed easily if required
			clone.insertAfter($('#attribute_options_group_ul li').last()); // insert after the last element
			clone.children('input').focus();
			$('#attribute_options_count').val($('#attribute_options_group_ul li').size());
			/* Added to remove attribute selection of added clone */
			remove_attr_sel_index = $('#attribute_options_group_ul li').children('a.setAttributeOption').size() - 1;
			remove_attr_sel_elem = $('#attribute_options_group_ul li').children('a.setAttributeOption').get()[remove_attr_sel_index];
			removeSelected(remove_attr_sel_elem);
			return false; // to prevent the default action anchor
		}

		/** Removes the attribute option
		*	Note: attribute_options_count is set here and in addClone function **/
		function removeClone(elem)
		{
			// if childNodes length is 3 then atleast one input element is present
			if ($(elem).parent().parent().children().size() > 1)
			{
				$(elem).parent().remove();
			}
			$('#attribute_options_count').val($('#attribute_options_group_ul li').size());
		}

		/** Sets the attribute option as the default value. This also is set as the attribute default value. **/
		function setSelected(elem)
		{
			if ($(elem).parent().children('input').val() == '')
			{
				$('#dialog-err-msg-content').html('{{ trans('webshoppack::admin/manageCategory.add-attribute.attribute_option_err_add') }}');
				$("#dialog-err-msg").dialog({ title: '{{ trans('webshoppack::admin/manageCategory.product_attribute_title') }}', modal: true,
					buttons: { "{{ trans('webshoppack::common.ok') }}": function() { $(this).dialog("close"); } }
				});
			}
			else
			{
				$('#attribute_default_value').val($(elem).parent().children('input').val());
				$('.setAttributeOption').css('font-weight','normal');
				$(elem).css('font-weight','bold');
				//$(elem).parent().children('input').css('background-color','#efefef');
			}
		}

		$("#addAttributefrm").submit(function()
		{
			if ($("#addAttributefrm").validate().form())
			{
				formBuilderAddListRow();
			}
			return false;
		});

		var mes_required = '{{ trans('webshoppack::common.required') }}';
		$("#addAttributefrm").validate({
			rules: {
				attribute_label: {
					required: true
			    },
			    attribute_question_type: {
			    	required: true
			    }<?php
			    	$validation_rules = \Config::get('webshoppack::validation_rules');
			    	foreach($validation_rules as $validation_rule ){
			    		if ($validation_rule['input_box']) {
			    			echo ',' . "\n\t\t\t" .$validation_rule['name'] . '_input: {'.  "\n\t\t\t" ;
				    		if ( (boolean)$validation_rule['validation']) {
				    			$vr = explode("|", $validation_rule['validation']);
								// Fixed js error because of extra one ',' at last
				    			$first_rule = true;
				    			foreach($vr as $v){
				    				if(!$first_rule)
				    				{
				    					echo ','. "\n\t\t\t";
				    				}
				    				echo $v . ': true';
				    				$first_rule = false;
				    			}
				    		}
				    		echo '}';
			    		}
			    	}
			    ?>
				},

			messages: {
				title: {
					required: mes_required
				},
				attribute_question_type: {
						required: mes_required
					}<?php
						$validation_rules = \Config::get('webshoppack::validation_rules');
						reset($validation_rules);
				    	foreach($validation_rules as $validation_rule ){
				    		if ($validation_rule['input_box']) {
					    		echo ',' . "\n\t\t\t" .$validation_rule['name'] . '_input: {' . "\n\t\t\t" ;
					    		if ( (boolean)$validation_rule['validation']) {
					    			$vr = explode("|", $validation_rule['validation']);
									// Fixed js error because of extra one ',' at last
					    			$first_question = true;
					    			foreach($vr as $v){
					    				if(!$first_question)
					    				{
					    					echo ','. "\n\t\t\t";
					    				}
					    				echo $v . ': "'. trans('webshoppack::common.common_err_tip_'. $v) . '"';
					    				$first_question = false;
					    			}
					    		}
					    		echo '}';
				    		}
				    	}
				    ?>
				}
		});

		/** Adds new attribute to the DB and generates html and adds the same to the attribute list **/
		function formBuilderAddListRow()
		{
			$('#attribute_question_type').removeAttr('disabled');
			var data = $('#addAttributefrm').serialize();
			catalogOpenLoadingDialog();
			var url = '{{URL::action('Agriya\Webshoppack\AdminProductAttributesController@postAdd')}}';
			if($('#attribute_action').val() == 'update_attribute')
			{
				url = '{{URL::action('Agriya\Webshoppack\AdminProductAttributesController@postUpdate')}}';
			}
			$.post(url, data,  function(data)
			{
				var returnedData = JSON.parse(data);
				if (returnedData.err)
				{
					$('#ajaxMsgs').html(returnedData.err_msg);
					$('#ajaxMsgs').show();
				}
				else
				{
					if (returnedData.list_row)
					{
						if ($('#attribute_action').val() == 'add_attribute')
						{
							$('.formBuilderListBody').append(returnedData.list_row);
						}
						else
						{
							if ($('#attribute_id').val())
							{
								var prevObj = $('#formBuilderRow_' + $('#attribute_id').val()).prev();
								$('#formBuilderRow_' +$('#attribute_id').val()).remove();
								prevObj.after(returnedData.list_row);
							}
						}

						$('.formBuilderTable').tableDnDUpdate(); // updates table to respond to tableDND events
						initializeEditDelete(); // initialize mouseover and mouseout events to show and hide the edit/delete buttons on table lists
					}
					resetAddForm();
				}
				catalogCloseLoadingDialog();
				if(!returnedData.err)
				{
					if($('#attribute_action').val() == 'add_attribute')
					{
						$('#ajaxMsgSuccess').html('{{ trans('webshoppack::admin/manageCategory.add-attribute.attribute_added_success') }}').show().fadeOut(2000);
					}
					else
					{
						if(returnedData.unremoved_options_count == 0)
						{
							$('#ajaxMsgSuccess').html('{{ trans('webshoppack::admin/manageCategory.add-attribute.attribute_updated_success') }}').show().fadeOut(2000);
						}
						else
						{
							$('#ajaxMsgSuccess').html('{{ trans('webshoppack::admin/manageCategory.add-attribute.attribute_updated_success') }}'+returnedData.unremoved_options_count+' {{ trans('webshoppack::admin/manageCategory.add-attribute.attribute_options_in_use_msg') }}').show().fadeOut(2000);
						}
						$('#attribute_action').val('add_attribute');
					}
				}

			});
			return false; // for not allowing to submit the form
		}

		/* open loading dialog */
		function catalogOpenLoadingDialog()
		{
			$('#catalogLoadingImageDialog').dialog({
				open: function() {
		    		$(this).parents(".ui-dialog:first").find(".ui-dialog-titlebar-close").remove();
		  		},
				height: 'auto',
				width: 'auto',
				modal: true,
				title: '{{ trans('webshoppack::common.loading') }}'
			});
		}
		/* close loading dialog */
		function catalogCloseLoadingDialog()
		{
			$('#catalogLoadingImageDialog').dialog("close");
		}

		function formBuilderEditListRow(row_id)
		{
			var set_attribute_option = '';
			var data_params = 'row_id=' + row_id;
			catalogOpenLoadingDialog();
			$.getJSON('{{URL::action('Agriya\Webshoppack\AdminProductAttributesController@getAttributesRow')}}', data_params,function(data)
			{
				showAddForm(row_id);

				$('#attribute_options_json').val(data.options);
				$('#attribute_id').val(row_id);
				$('#attribute_label').val(data.attribute_label);
				$('#description').val(data.description);
				$('#attribute_question_type').val(data.attribute_question_type);
				$('#attribute_is_searchable_' + data.attribute_is_searchable).attr('checked','checked');
				$('#status_' + data.attribute_status).attr('checked','checked');

				showOptions();
				$('#attribute_default_value').val(data.default_value);

				if (data.validation_rules)
				{
					validation = data.validation_rules.split('|');

					for ( i = 0 ; i < validation.length; i++)
					{
						if (validation[i].search('-') != -1)
						{
							validation_text_arr = validation[i].split('-');
							validation_text = validation_text_arr[0];
							$('#' + validation_text + '_input').parent().show('li');
							$('#' + validation_text + '_input').removeAttr('disabled');
							$('#' + validation_text + '_input').val(validation_text_arr[1]);

						}
						else
						{
							validation_text = validation[i];
						}
						$('#' + validation_text).attr('checked',true);
					}
				}

				if (data.options_size > 0)
				{
					// first input box is always available and empty other can be cloned using this. while edit data is updated in first input box.
					// and then clone needed and update it as necessary
					$('#attribute_options_group_ul li').children('input').val(data.options[0].option_label);
					if(data.options[0].id)
					{
						$('#attribute_options_group_ul li').children('input').attr('name', attribute_options_edit_name + data.options[0].id);
					}
					else
					{
						$('#attribute_options_group_ul li').children('input').attr('name','attribute_options[]');
					}


				    if(data.options[0].is_default_option == 'yes')
					{
						set_attribute_option = $('#attribute_options_group_ul li').children('a.setAttributeOption').get()[0];
					}
					/* ends to set first row options labels in all languages */
					for ( i = 1 ; i <  data.options_size ; i++ )
					{
						addClone(data.options[i].option_label, data.options[i].id);

						if(data.options[i].is_default_option == 'yes')
						{
							set_attribute_option = $('#attribute_options_group_ul li').children('a.setAttributeOption').get()[i];
						}
					}
					setSelected(set_attribute_option);
				}

				if (data.options_used)
				{
					//$('#attribute_options_already_used').show();
					//$('#attribute_question_type').attr('disabled',true);
				}

				catalogCloseLoadingDialog();
			});
		}

		function formBuilderRemoveListRow(row_id)
		{
			$("#dialog-delete-confirm").dialog({ title: '{{ trans('webshoppack::admin/manageCategory.product_attribute_title') }}', modal: true,
				buttons: {
					"Yes": function() {
						$(this).dialog("close");
						$.getJSON('{{URL::action('Agriya\Webshoppack\AdminProductAttributesController@getAttributesDelete')}}?row_id=' + row_id,
						{
							beforeSend:function()
							{
								catalogOpenLoadingDialog();
							}
						},
						function(data)
						{
							catalogCloseLoadingDialog();
							if(data.result == 'success')
							{
								$('#formBuilderRow_' + data.row_id).remove();
							}
							else
							{
								if(data.err_msg)
								{
									$('#dialog-err-msg-content').html(data.err_msg);
								}
								else
								{
									$('#dialog-err-msg-content').html('{{ trans('webshoppack::admin/manageCategory.delete-attribute.delete_attribute_operation_err') }}');
								}
								$("#dialog-err-msg").dialog({ title: '{{ trans('webshoppack::admin/manageCategory.product_attribute_title') }}', modal: true,
									buttons: { "{{ trans('webshoppack::common.ok') }}": function() { $(this).dialog("close"); } }
								});
							}

						});
					}, "{{ trans('webshoppack::common.cancel') }}": function() { $(this).dialog("close"); }
				}
			});
			return false;
		}
	</script>
@stop