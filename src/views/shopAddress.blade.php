@if (isset($success_message) && $success_message != "")
    <div class="alert alert-success">{{	$success_message }}</div>
@endif
{{ Form::model($shop_details, ['url' => URL::to(Config::get('webshoppack::shop_uri').'/users/shop-details'),'method' => 'post','id' => 'shopaddress_frm', 'class' => 'form-horizontal']) }}
	{{ Form::hidden('submit_form', "update_address", array("name" => "submit_form", "id" => "submit_form"))}}
	<fieldset class="col-lg-10">
		<div class="form-group {{{ $errors->has('shop_country') ? 'error' : '' }}}">
			{{ Form::label('shop_country', trans("webshoppack::shopDetails.shop_country"), array('class' => 'col-lg-3 control-label')) }}
			<div class="col-lg-6">
				{{ Form::select('shop_country', $country_arr, Input::get('shop_country'), array('class' => 'control-label selectpicker')) }}
				<label class="error">{{{ $errors->first('shop_country') }}}</label>
			</div>
		</div>

		<div class="form-group {{{ $errors->has('shop_address1') ? 'error' : '' }}}">
			{{ Form::label('shop_address1', trans("webshoppack::shopDetails.shop_address1"), array('class' => 'col-lg-3 control-label')) }}
			<div class="col-lg-4">
				{{ Form::text('shop_address1', Input::get('shop_address1'), array('class' => 'form-control')); }}
				<label class="error">{{{ $errors->first('shop_address1') }}}</label>
			</div>
		</div>

		<div class="form-group {{{ $errors->has('shop_address2') ? 'error' : '' }}}">
			{{ Form::label('shop_address2', trans("webshoppack::shopDetails.shop_address2"), array('class' => 'col-lg-3 control-label')) }}
			<div class="col-lg-4">
				{{ Form::text('shop_address2', Input::get('shop_address2'), array('class' => 'form-control')); }}
				<label class="error">{{{ $errors->first('shop_address2') }}}</label>
			</div>
		</div>

		<div class="form-group {{{ $errors->has('shop_city') ? 'error' : '' }}}">
			{{ Form::label('shop_city', trans("webshoppack::shopDetails.shop_city"), array('class' => 'col-lg-3 control-label')) }}
			<div class="col-lg-4">
				{{ Form::text('shop_city', Input::get('shop_city'), array('class' => 'form-control')); }}
				<label class="error">{{{ $errors->first('shop_city') }}}</label>
			</div>
		</div>

		<div class="form-group {{{ $errors->has('shop_state') ? 'error' : '' }}}">
			{{ Form::label('shop_state', trans("webshoppack::shopDetails.shop_state"), array('class' => 'col-lg-3 control-label')) }}
			<div class="col-lg-4">
				{{ Form::text('shop_state', Input::get('shop_state'), array('class' => 'form-control')); }}
				<label class="error">{{{ $errors->first('shop_state') }}}</label>
			</div>
		</div>

		<div class="form-group {{{ $errors->has('shop_zipcode') ? 'error' : '' }}}">
			{{ Form::label('shop_zipcode', trans("webshoppack::shopDetails.shop_postalzip"), array('class' => 'col-lg-3 control-label')) }}
			<div class="col-lg-4">
				{{ Form::text('shop_zipcode', Input::get('shop_zipcode'), array('class' => 'form-control')); }}
				<label class="error">{{{ $errors->first('shop_zipcode') }}}</label>
			</div>
		</div>

		<div class="form-group">
			<div class="col-lg-offset-3 col-lg-10">
				<button type="button" name="update_address" class="btn btn-success" id="update_address" value="update_address" onclick="javascript:doSubmit('shopaddress_frm', 'address_details');">{{trans("webshoppack::common.update")}}</button>
			</div>
		</div>
	</fieldset>
{{ Form::close() }}