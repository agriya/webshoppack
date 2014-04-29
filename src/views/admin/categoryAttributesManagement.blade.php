<div id="attributes_block">
	<div id="dialog-attribute-remove-confirm" title="" style="display:none;">
	    <span class="ui-icon ui-icon-alert"></span>
		<span class="show ml15">{{ trans('webshoppack::admin/manageCategory.remove-association.remove_association_confirm') }}</span>
	</div>

	<div class="page-header mb20">
		<a href="javascript:void(0);" onclick="window.location.href='{{URL::to('admin/product-attributes')}}';" title="{{ trans('webshoppack::admin/manageCategory.add_new_attribute') }}" class="btn btn-info btn-xs pull-right ml15"><i class="icon-plus-sign"></i> {{ trans('webshoppack::admin/manageCategory.add_new_attribute') }}</a>
		@if($d_arr['category_id'] != $d_arr['root_category_id'] && $attribs_arr['parent'])
		<a href="javascript:void(0);" onclick="displayParentCategoryAttributes();" title="{{ trans('webshoppack::admin/manageCategory.add_new_attribute') }}" id="linkShowParentAttributes" class="clsHide btn btn-info btn-xs pull-right"><i class="icon-plus-sign"></i> {{ trans('webshoppack::admin/manageCategory.parent_category_attribute') }}</a>
		@endif
		<h1>{{ trans('webshoppack::admin/manageCategory.attributes_management_title') }}</h1>
	</div>

	@if($d_arr['category_id'] != $d_arr['root_category_id'])
		<?php $cat_name = $attr_service_obj->getCategoryName($d_arr['category_id']); ?>
		<div class="page-header"><h1>{{ $cat_name }}</h1></div>
	@else
		<p class="alert alert-info">No category Selected</p>
	@endif

	@if($d_arr['category_id'] != $d_arr['root_category_id'] && $attribs_arr['parent'])
		{{-- Attributes assigned in parent categories starts --}}
		<div id="parentAttributesBlock" style="display:none;">
		    <h4>{{ trans('webshoppack::admin/manageCategory.parent_category_attribute_title') }}</h4>
		    <table class="table table-striped table-bordered table-hover mb30">
		        <tbody>
		            <tr class="nodrag nodrop">
		                <th class="col-lg-4">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_label') }}</th>
		                <th class="col-lg-6">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_type') }}</th>
		                <th>{{ trans('webshoppack::common.action') }}</th>
		            </tr>

		            @foreach($attribs_arr['parent'] as $inc => $value)
			            <tr class="nodrag nodrop formBuilderRow">
			                <td>
			                	{{ $value['attribute_label'] }}
			                </td>
			                <td>
			                    {{ $attr_service_obj->getHTMLElement($value['attribute_question_type'], $value['attribute_options'], $value['default_value']) }}
			                </td>
			                <td class="formBuilderAction clsUnasinedAtributes">
			                    <a class="formBuilderRowView grey" style="display: none;" href="{{URL::action('Agriya\Webshoppack\AdminCategoryAttributesController@getViewAttribute')}}?attribute_id={{ $value['attribute_id'] }}" title="{{ trans('webshoppack::admin/manageCategory.view_attribute') }}" id="formBuilderRowView_{{ $value['attribute_id'] }}">
								<i class="icon-eye-open bigger-130"></i></a>
			                </td>
			            </tr>
		            @endforeach
		        </tbody>
		    </table>
		</div>
	@endif

	@if($d_arr['category_id'] && $d_arr['category_id'] != $d_arr['root_category_id'])
		{{ Form::hidden('attr_mgmt_category_id', $d_arr['category_id'], array("id" => "attr_mgmt_category_id")) }}
		<div class="alert alert-info">
			<strong>{{ trans('webshoppack::common.note') }}:</strong> {{ trans('webshoppack::admin/manageCategory.attribute_assign_msg') }}
		</div>
	@endif
	<div id="ajaxMsgs" class="alert alert-danger" style="display:none;"></div>
	<div id="ajaxMsgSuccess" class="alert alert-success" style="display:none;"></div>
	<div id="sample-table-1">
		@if($d_arr['category_id'] && $d_arr['category_id'] != $d_arr['root_category_id'])
			<h4>{{ trans('webshoppack::admin/manageCategory.assigned_attributes') }}</h4>
		@endif
		<table id="attrdnd" class="formBuilderAssignedTable table table-striped table-bordered table-hover mb50">
			<tbody class="formBuilderAssignedListBody">
				<tr class="nodrag nodrop">
					<th class="col-lg-4">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_label') }}</th>
					<th class="col-lg-6">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_type') }}</th>
					<th>{{ trans('webshoppack::common.action') }}</th>
				</tr>
				@if($d_arr['category_id'] == $d_arr['root_category_id'])
					<tr class="nodrag nodrop">
						<td colspan="3">
							<p class="alert alert-info">{{ trans('webshoppack::admin/manageCategory.category_not_selected') }}</p>
						</td>
					</tr>
				@else
					@if(count($attribs_arr['assigned']) > 0)
						@foreach($attribs_arr['assigned'] as $inc => $value)
							<tr id="formBuilderRow_{{$value['attribute_id'] }}" class="formBuilderRow formAssignedAttributes">
								<td>
									{{$value['attribute_label'] }}
								</td>
								<td class="multi-select">
									{{ $attr_service_obj->getHTMLElement($value['attribute_question_type'], $value['attribute_options'], $value['default_value']) }}
								</td>
								<td class="formBuilderAction clsUnasinedAtributes">
									<a class="formBuilderRowDelete red" onclick="javascript:formBuilderRemoveListRow({{$value['attribute_id'] }}, {{$d_arr['category_id']}});" style="display: none;" href="javascript: void(0);" title="{{ trans('webshoppack::admin/manageCategory.remove_attribute') }}"><i class="icon-trash bigger-130"></i></a>&nbsp;
									<a class="formBuilderRowView grey" style="display: none;" href="{{URL::action('Agriya\Webshoppack\AdminCategoryAttributesController@getViewAttribute')}}?attribute_id={{ $value['attribute_id'] }}" title="{{ trans('webshoppack::admin/manageCategory.view_attribute') }}" id="formBuilderRowView_{{$value['attribute_id'] }}">
									<i class="icon-eye-open bigger-130"></i></a>
								</td>
							</tr>
						@endforeach
					@else
						<tr class="nodrag nodrop noAttributeAssignedRow">
							<td colspan="3">
								<p class="alert alert-info">{{ trans('webshoppack::admin/manageCategory.click_assign_attributes_msg') }}</p>
							</td>
						</tr>
					@endif
				@endif
			</tbody>
		</table>

		@if($d_arr['category_id'] && $d_arr['category_id'] != $d_arr['root_category_id'])
			<h4>{{ trans('webshoppack::admin/manageCategory.unassigned_attributes') }}</h4>
			<table class="formBuilderAddedTable table table-striped table-bordered table-hover">
				<tbody class="formBuilderAddedListBody">
					<tr class="nodrag nodrop">
						<th class="col-lg-4">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_label') }}</th>
						<th class="col-lg-6">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_type') }}</th>
						<th>{{ trans('webshoppack::common.action') }}</th>
					</tr>
					@if(count($attribs_arr['new']) > 0)
						@foreach($attribs_arr['new'] as $inc => $value)
							<tr id="formBuilderNewRow_{{$value['attribute_id'] }}" class="nodrag nodrop formBuilderAddRow formUnassignedAttributes" title="{{ trans('webshoppack::admin/manageCategory.double_click_assign_attributes_msg') }}">
								<td>{{$value['attribute_label'] }}</td>
								<td class="multi-select">{{ $attr_service_obj->getHTMLElement($value['attribute_question_type'], $value['attribute_options'], $value['default_value']) }}</td>
								<td class="formBuilderAction clsUnasinedAtributes">
									<a class="formBuilderRowEdit green" onclick="javascript:formBuilderAddListRow({{$value['attribute_id'] }}, {{$d_arr['category_id']}});" style="display: none;" href="javascript: void(0);" title="{{ trans('webshoppack::admin/manageCategory.assign_attribute_title') }}"><i class="icon-share bigger-130"></i> </a>&nbsp;
									<a class="formBuilderRowView grey" style="display: none;" href="{{URL::action('Agriya\Webshoppack\AdminCategoryAttributesController@getViewAttribute')}}?attribute_id={{ $value['attribute_id'] }}" title="{{ trans('webshoppack::admin/manageCategory.view_attribute') }}" id="formBuilderRowView_{{$value['attribute_id'] }}"><i class="icon-eye-open bigger-130"></i> </a>
								</td>
							</tr>
						@endforeach
					@else
						<tr class="nodrag nodrop noAttributeAddedRow">
							<td colspan="3">
								<p class="alert alert-info">{{ trans('webshoppack::admin/manageCategory.attributes_not_found') }}</p>
							</td>
						</tr>
					@endif
				</tbody>
			</table>
		@endif
	</div>
</div>