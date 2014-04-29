@if (isset($success_message) && $success_message != "")
    <div class="alert alert-success">{{	$success_message }}</div>
@endif
{{ Form::model($shop_paypal_details, ['url' => URL::to(Config::get('webshoppack::shop_uri').'/users/shop-details'),'method' => 'post','id' => 'shoppaypal_frm', 'class' => 'form-horizontal']) }}
	<div class="mb20 col-lg-9">
		<p class="alert alert-info"><strong>{{ trans("webshoppack::common.note") }}: </strong>{{ trans("webshoppack::shopDetails.shop_paypal_info") }}</p>
	</div>
	<?php
		$paypal_email_label = trans("webshoppack::shopDetails.paypal_email_label");
	?>
	{{ Form::hidden('submit_form', "update_shop_paypal", array("name" => "submit_form", "id" => "submit_form"))}}
	<fieldset class="col-lg-10">
		<div class="form-group {{{ $errors->has('shop_name') ? 'error' : '' }}}">
			{{ Form::label('paypal_email', $paypal_email_label, array('class' => 'col-lg-3 control-label required-icon')) }}
			<div class="col-lg-4">
				{{ Form::text('paypal_id', Input::get('paypal_id'), array('class' => 'form-control')); }}
				<label class="error">{{{ $errors->first('paypal_email') }}}</label>
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-offset-3 col-lg-10">
				<button type="button" name="update_policy" class="btn btn-success" id="update_policy" value="update_policy" onclick="javascript:doSubmit('shoppaypal_frm', 'paypal_details');">{{trans("webshoppack::common.update")}}</button>
			</div>
		</div>
	</fieldset>
{{ Form::close() }}