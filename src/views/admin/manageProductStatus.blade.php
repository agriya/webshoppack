@extends('webshoppack::adminPopup')
@section('content')
    <div class="clearfix popup-title">
        <h2>{{ trans('webshoppack::admin/productList.change_status') }}</h2>
    </div>

	@if(Session::has('success_message') && Session::get('success_message') != '')
		<div class="quotehistroy-pad page-content">
			<div class="alert alert-success">{{ Session::get('success_message') }}</div>
			<div class="control-group">
				<div class="controls">
					<button type="reset" name="close_change_status" value="close_change_status" class="btn btn-sm btn-danger" onclick="javascript:closeDialog();">
					<i class="icon-remove bigger-110 danger"></i> {{ trans('webshoppack::common.close') }}</button>
				</div>
			</div>
			<?php Session::forget('success_message'); ?>
		</div>
	@elseif(Session::has('error_message') && Session::get('error_message') != '')
		<div class="quotehistroy-pad page-content">
			<div class="alert alert-danger">{{ Session::get('error_message') }}</div>
			<div class="control-group">
				<div class="controls">
					<button type="reset" name="close_change_status" value="close_change_status" class="btn btn-sm btn-danger" onclick="javascript:cancelDialog();">
					<i class="icon-remove bigger-110 danger"></i> {{ trans('webshoppack::common.close') }}</button>
				</div>
			</div>
			<?php Session::forget('error_message'); ?>
		</div>
	@endif
	@if($allow_to_view_form)
		<div class="quotehistroy-pad page-content custom-horizon">
			<div class="popup-oflow">
				<div class="popup-ovrflow">
					<form name="frmManageProductStatus" id="frmManageProductStatus" class="form-horizontal form-request" method="post">
						<div class="form-group">
							{{ Form::label('product_status', trans('webshoppack::admin/productList.change_status_to'), array('class' => 'col-sm-3 control-label required-icon')) }}
							<div class="col-sm-9">
								{{ Form::select('product_status', $d_arr['status_drop'], Input::get("product_status"),array('class' => 'selectpicker')) }}
								<label class="error">{{{ $errors->first('product_status') }}}</label>
							</div>
						</div>

						<div class="form-group">
							{{ Form::label('comment', trans('webshoppack::admin/productList.comment_label'), array('class' => 'col-sm-3 control-label required-icon')) }}
							<div class="col-sm-9">
								{{ Form::textarea('comment', Input::get("comment"), array ('class' => 'col-xs-10 col-sm-9', 'rows' => 5)) }}
								<label class="error">{{{ $errors->first('comment') }}}</label>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-3">
								{{ Form::hidden('p_id', $p_id, array('id' => 'p_id')) }}
								<button type="submit" id="change_status" name="change_status" value="change_status" class="btn btn-success btn-sm">
								<i class="icon-arrow-right icon-on-right bigger-110"></i> {{ trans('webshoppack::common.submit') }}</button>
								<button type="reset" name="cancel_change_status" value="cancel_change_status" class="btn btn-sm" onclick="javascript:cancelDialog();">
								<i class="icon-remove bigger-110"></i> {{ trans('webshoppack::common.cancel') }}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	@endif
@stop
@section('script_content')
    <script type="text/javascript">
    var mes_required = '{{ trans('webshoppack::common.required') }}';
    @if($allow_to_view_form)
		$(document).ready(function() {
	        $("#frmManageProductStatus").validate({
	            rules: {
	                product_status: {
	                    required: true
	                },
	                comment : {
	                    required: true
	                }
	            },
	            messages: {
	                product_status: {
	                    required: mes_required
	                },
	                comment : {
	                    required: mes_required
	                }
	            }
	        });
	    });
	  @endif
		function closeDialog()
		{
			parent.window.location.href = "{{ URL::to(Config::get('webshoppack::admin_uri').'/list') }}";
		}
		function cancelDialog() {
			parent.$.fancybox.close();
		}
    </script>

@stop
