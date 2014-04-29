@extends('webshoppack::adminPopup')
@section('content')
	<div class="popup-title">
        <h2>{{ trans('webshoppack::admin/manageCategory.view_attribute') }}</h2>
    </div>
	<div class="popup-oflow">
        <div class="form-horizontal form-request">
		    <div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_label') }} :</label>
				<div class="col-sm-5 mt5">{{$attribute_details['attribute_label']}}</div>
		    </div>
			@if($attribute_details['description'])
		    	<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('webshoppack::admin/manageCategory.add-attribute.attribute_description') }} :</label>
					<div class="col-sm-5 mt5">{{$attribute_details['description']}}</div>
				</div>
		    @endif
		    <div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_type') }} :</label>
				<div class="col-sm-5 mt5 multi-select radio-checkbox">
					{{$attr_service_obj->getHTMLElement($attribute_details['attribute_question_type'], $attribute_details['attribute_options'], $attribute_details['default_value'])}}
				</div>
			</div>

			@if($attribute_details['validation_rules'])
		    	<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_validation') }} :</label>
					<div class="col-sm-5 mt5">{{$attribute_details['validation_rules']}}</div>
				</div>
		    @endif
		    @if($attribute_details['default_value'])
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('webshoppack::admin/manageCategory.list-attribute.attribute_default_value') }} :</label>
					<div class="col-sm-5 mt5">{{$attribute_details['default_value']}}</div>
		    	</div>
			@endif
		    <div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('webshoppack::admin/manageCategory.add-attribute.attribute_is_searchable') }} :</label>
				<div class="col-sm-5 mt5">
					<?php
						$lbl_class = "";
						if(strtolower ($attribute_details['is_searchable']) == "yes")
							$lbl_class = "text-success";
						elseif(strtolower ($attribute_details['is_searchable']) == "no")
							$lbl_class = "text-danger";
					?>
					<span class="{{ $lbl_class }}">{{ $attribute_details['is_searchable'] }}</span>
				</div>
		    </div>
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('webshoppack::common.status') }} :</label>
				<div class="col-sm-5 mt5">
					<?php
						$lbl_class = "";
						if(strtolower ($attribute_details['status']) == "active")
							$lbl_class = "label-success";
						elseif(strtolower ($attribute_details['status']) == "inactive")
							$lbl_class = "label-grey arrowed-in arrowed-in-right";
					?>
					<span class="label {{ $lbl_class }}">{{ $attribute_details['status'] }}</span>
				</div>
			</div>
		</div>
	</div>
@stop