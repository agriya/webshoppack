@extends(\Config::get('webshoppack::base_view'))
@section('content')
<div class="add-product">
	@if(Session::has('error_message') && Session::get('error_message') != '')
        <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
        <?php Session::forget('error_message'); ?>
    @endif
    @if(Session::has('success_message') && Session::get('success_message') != '')
        <div class="alert alert-success">{{ Session::get('success_message') }}
      	@if(Session::has('final_success') && (Session::get('final_success') == 1) )
			<?php $view_url =  $service_obj->getProductViewURL($p_id);?>
			<a href= "{{$view_url}}">View Product</a>
    	@endif
    	</div>
        <?php Session::forget('success_message'); ?>
    @endif

    @if(Session::has('validate_tab_arr') && count(Session::get('validate_tab_arr')) > 0 )
	    <?php
	    	$validate_tab_arr = array_filter(Session::get('validate_tab_arr'), function($value){ return ($value)? false: true;});
	    ?>
    	<div class="alert alert-danger">
    		{{{ trans('webshoppack::product.tab_validation_err') }}}
	    	@foreach($validate_tab_arr AS $key => $value)
	    		<p>{{ $service_obj->p_tab_lang_arr[$key] }}</p>
	        @endforeach
	    </div>
	    <?php Session::forget('validate_tab_arr'); ?>
    @endif

	<?php
        $header_title = ($action == 'add')? 'add_title' : 'edit_title';
    ?>
    @if(!Session::has('final_success') || Session::get('final_success') != 1)
    <?php Session::forget('final_success'); ?>
    <h1 class="title-one">{{ trans('webshoppack::product.'.$header_title) }}</h1>
	<div class="clearfix">
    	<div class="pull-right ststus-block">
	        <?php
	        $status = trans('webshoppack::product.status_in_draft');
			$lbl_class = "label-info";
	        if(count($p_details) > 0)
	        {
	            if($p_details['product_status'] == 'Ok')
	            {
	                $status = trans('webshoppack::product.status_active');
					$lbl_class = "label-success";
	            }
	            elseif($p_details['product_status'] == 'ToActivate')
	            {
	                $status = trans('webshoppack::product.status_to_activate');
					$lbl_class = "label-warning";
	            }
	            elseif($p_details['product_status'] == 'NotApproved')
	            {
	                $status = trans('webshoppack::product.status_in_not_approved');
					$lbl_class = "label-danger";
	            }
	        }
	        ?>
        	<p>{{ trans('webshoppack::product.product_status_caption') }}: <span id="product_status_text" class="label {{ $lbl_class }}">{{ $status }}</span></p>
    	</div>

		<!--- Start Menu --->
	    <div class="accountmenu-view">
	        <a href="#" class="menupage custom-menu-toggler" style="display:none;"><i class="fa fa-list"></i> View Menu</a>
	        <div class="showpage">
	            <ul class="nav nav-tabs custom-tabs mobile-menu clearfix">
	                @foreach($d_arr['tab_list'] AS $tab_index => $value)
	                    <?php
	                        $activeClass = ($tab_index == $d_arr['p']) ? 'active' : '';
	                        $link = ($value) ? URL::to(\Config::get('webshoppack::uri').'/add?p='.$tab_index.'&id='.$p_id) : 'javascript:void(0)';
	                    ?>
	                    <li class="{{ $activeClass }}"><a href="{{ $link }}"><span>{{ $service_obj->p_tab_lang_arr[$tab_index] }}</span></a></li>
	                @endforeach
	            </ul>
	        </div>
	    </div>
	    <!--- End Menu --->
 	</div>


	{{-- Basic form start --}}
	@if($d_arr['p'] == 'basic')
	  {{ Form::model($p_details, [
	                        'url' => $p_url,
	                        'method' => 'post',
	                        'id' => 'addProductBasicfrm', 'class' => 'form-horizontal form-request'
	                        ]) }}
	    <fieldset class="well">

            @if($d_arr['p'] != 'publish' && $d_arr['p'] != 'preview_files')
                <p class="text-danger ml15">{{{ trans('webshoppack::product.required_text') }}}</p>
            @endif

	    	<div class="form-group {{{ $errors->has('product_name') ? 'error' : '' }}}">
				{{ Form::label('product_name', trans('webshoppack::product.title'), array('class' => 'col-lg-3 control-label required-icon')) }}
				<div class="col-lg-6">
			        {{  Form::text('product_name', null, array('class' => 'form-control')); }}
			        <label class="error">{{{ $errors->first('product_name') }}}</label>
				</div>
			</div>

			<div class="form-group {{{ $errors->has('product_description') ? 'error' : '' }}}">
				{{ Form::label('product_description', trans('webshoppack::product.description'), array('class' => 'col-lg-3 control-label')) }}
				<div class="col-lg-8">
			        {{  Form::textarea('product_description', null, array('class' => 'form-control fn_editor')); }}
		        	<label class="error">{{{ $errors->first('product_description') }}}</label>
				</div>
			</div>


			<div class="form-group {{{ $errors->has('product_description') ? 'error' : '' }}}">
				{{ Form::label('product_support_content', trans('webshoppack::product.support_content'), array('class' => 'col-lg-3 control-label')) }}
				<div class="col-lg-8">
			        {{  Form::textarea('product_support_content', null, array('class' => 'form-control fn_editor')); }}
		        	<label class="error">{{{ $errors->first('product_support_content') }}}</label>
				</div>
			</div>

			<div class="form-group {{{ $errors->has('product_highlight_text') ? 'error' : '' }}}">
				{{ Form::label('product_highlight_text', trans('webshoppack::product.summary'), array('class' => 'col-lg-3 control-label')) }}
				<div class="col-lg-6 custom-textarea">
			        {{  Form::textarea('product_highlight_text', null, array('class' => 'form-control', 'placeholder' => str_replace('VAR_LIMIT', Config::get('webshoppack::summary_max_length'), trans('webshoppack::product.summary_place_holder')) )); }}
		        	<label class="error">{{{ $errors->first('product_highlight_text') }}}</label>
				</div>
			</div>

			<div class="form-group {{{ $errors->has('product_category_id') ? 'error' : '' }}}">
				{{ Form::label('product_category_id', trans('webshoppack::product.category'), array('class' => 'col-lg-3 control-label required-icon')) }}
				<div id="sub_categories" class="col-lg-3">
			        @if($d_arr['top_cat_count'] <=1)
					{{ Form::select('product_category_id', $category_main_arr, $category_id, array('class' => 'control-label selectpicker-2 mt15 ', 'onchange' => "listSubCategories('product_category_id', '1')", 'id' => 'product_category_id')) }}
					@else
				        <?php $i = 1; ?>
				        @foreach($category_sub_arr AS $cat_id => $sub_arr)
				        	<?php
								$drop_name = ($i==1)? 'product_category_id' : 'sub_category_id_'.$cat_id;
								$next_sel_class = 'fn_subCat_'.$cat_id;
								$selected_val = (isset($d_arr['top_cat_list_arr'][$i]))? $d_arr['top_cat_list_arr'][$i] : '';
								$i++;
							?>
							@if(count($sub_arr) > 1)
								{{ Form::select($drop_name, $sub_arr, $selected_val, array('id' => $drop_name, 'class' => 'control-label selectpicker-2 mt15 '.$next_sel_class, 'onchange' => "listSubCategories('".$drop_name."',". $cat_id.")")) }}
							@endif
				        @endforeach
				    @endif
			    </div>
				<div style="display:none" id="loading_sub_category" class="mt5"><img src="{{URL::asset('packages/agriya/webshoppack/images/general/loading.gif')}}" /></div>
			    <label class="error">{{{ $errors->first('product_category_id') }}}</label>
			</div>

			<div id="sel_addSection" class="form-group add-section" style="display:none;">
                {{ Form::label('section_name', trans('webshoppack::product.new_section_name'), array('class' => 'col-lg-3 control-label required-icon')) }}
                <div class="col-lg-3">
                    {{  Form::text('section_name', null, array('class' => 'form-control', 'id' => 'section_name')); }}
                    <label class="error fn_sectionErr">{{{ $errors->first('section_name') }}}</label>
                    <a href="javascript: void(0);" class="fn_saveSection text-success">{{ trans('webshoppack::product.save_section') }}</a> |
                    <a href="javascript: void(0);" class="fn_saveSectionCancel text-danger">{{ trans('webshoppack::common.cancel') }}</a>
                </div>
			</div>

			<div class="form-group {{{ $errors->has('user_section_id') ? 'error' : '' }}}">
				{{ Form::label('user_section_id', trans('webshoppack::product.section_name'), array('class' => 'col-lg-3 control-label')) }}
				<div class="col-lg-3 add-sectionlink">
			        {{ Form::select('user_section_id', $section_arr, null, array('class' => 'control-label selectpicker-2')) }}
			        <label class="error">{{{ $errors->first('user_section_id') }}}</label>
			        <p class="text-muted"><small>{{ trans('webshoppack::product.add_section_help') }}</small></p>
				</div>
				<strong class="mt5 disp-block"><a href="javascript: void(0);" class="fn_addSection text-info">{{ trans('webshoppack::product.add_section') }}</a></strong>
			</div>

			<div class="form-group {{{ $errors->has('demo_url') ? 'error' : '' }}}">
				{{ Form::label('demo_url', trans('webshoppack::product.demo_url'), array('class' => 'col-lg-3 control-label')) }}
				<div class="col-lg-3">
			        {{  Form::text('demo_url', null, array('class' => 'form-control')); }}
			        <label class="error">{{{ $errors->first('demo_url') }}}</label>
				</div>
			</div>

            <div class="form-group {{{ $errors->has('demo_details') ? 'error' : '' }}}">
				{{ Form::label('demo_details', trans('webshoppack::product.demo_details'), array('class' => 'col-lg-3 control-label')) }}
				<div class="col-lg-4 custom-textarea">
			        {{  Form::textarea('demo_details', null, array('class' => 'form-control')); }}
			        <label class="error">{{{ $errors->first('demo_details') }}}</label>
				</div>
			</div>

			<div class="form-group {{{ $errors->has('product_tags') ? 'error' : '' }}}">
				{{ Form::label('tags', trans('webshoppack::product.product_tags'), array('class' => 'col-lg-3 control-label required-icon')) }}
				<div class="col-lg-3">
			        {{  Form::text('product_tags', null, array('class' => 'form-control', 'placeholder' => trans('webshoppack::product.tags_place_holder') )); }}
			        <label class="error">{{{ $errors->first('product_tags') }}}</label>
				</div>
			</div>
		</fieldset>
		<h1 class="title-two">{{ trans('webshoppack::product.meta_details_title') }}</h1>
        <fieldset class="well">
			<div class="form-group {{{ $errors->has('meta_title') ? 'error' : '' }}}">
				{{ Form::label('meta_title', trans('webshoppack::product.meta_title'), array('class' => 'col-lg-3 control-label')) }}
				<div class="col-lg-4">
			        {{  Form::text('meta_title', null, array('class' => 'form-control')); }}
			        <label class="error">{{{ $errors->first('meta_title') }}}</label>
				</div>
			</div>

			<div class="form-group {{{ $errors->has('meta_description') ? 'error' : '' }}}">
				{{ Form::label('meta_description', trans('webshoppack::product.meta_description'), array('class' => 'col-lg-3 control-label')) }}
				<div class="col-lg-4 custom-textarea">
			        {{  Form::textarea('meta_description', null, array('class' => 'form-control')); }}
			        <label class="error">{{{ $errors->first('meta_description') }}}</label>
				</div>
			</div>

			<div class="form-group {{{ $errors->has('meta_keyword') ? 'error' : '' }}}">
				{{ Form::label('meta_keyword', trans('webshoppack::product.meta_keyword'), array('class' => 'col-lg-3 control-label')) }}
				<div class="col-lg-4 custom-textarea">
			        {{  Form::textarea('meta_keyword', null, array('class' => 'form-control')); }}
			        <label class="error">{{{ $errors->first('meta_keyword') }}}</label>
				</div>
			</div>

			<div class="form-group">
				{{ Form::hidden('id', $p_id, array('id' => 'id')) }}
				{{ Form::hidden('my_selected_categories', $d_arr['my_selected_categories'], array('id' => 'my_selected_categories')) }}
				{{ Form::hidden('my_category_id', $d_arr['my_category_id'], array('id' => 'my_category_id')) }}
				{{ Form::hidden('p', $d_arr['p'], array('id' => 'p')) }}
				@if($action == 'add')
				   <div class="col-lg-offset-3 col-lg-5">
				       <button name="add_product" id="add_product" value="add_product" type="submit" class="btn btn-success">{{ trans('webshoppack::product.save_and_proceed') }}</button>
				   </div>
				@else
				    <div class="col-lg-offset-3 col-lg-5">
				       <button name="edit_product" id="edit_product" value="edit_product" type="submit" class="btn btn-success">{{ trans('webshoppack::product.save_and_proceed') }}</button>
				   </div>
				@endif
			</div>
	    </fieldset>
		{{ Form::close() }}
	@endif
	{{-- Basic form end --}}

	{{-- Price form start --}}
	@if($d_arr['p'] == 'price')
		<?php
			$p_details['product_discount_fromdate'] = ($p_details['product_discount_fromdate'] != '0000-00-00')? date('d/m/Y', strtotime($p_details['product_discount_fromdate'])):'';
			$p_details['product_discount_todate'] = ($p_details['product_discount_todate'] != '0000-00-00')? date('d/m/Y', strtotime($p_details['product_discount_todate'])):'';
			//To set default price value is empty instead of 0.00
			$p_details['product_price'] = ($p_details['product_price'] == '0.00')? '' : $p_details['product_price'];
			$p_details['product_discount_price'] = ($p_details['product_discount_price'] == '0.00')? '' : $p_details['product_discount_price'];
		?>
	  {{ Form::model($p_details, [
	                        'url' => $p_url,
	                        'method' => 'post',
	                        'id' => 'addProductPricefrm', 'class' => 'form-horizontal form-request'
	                        ]) }}
	    <fieldset class="well">
	    	@if(Config::get("webshoppack::can_upload_free_product"))
		    	<div class="form-group {{{ $errors->has('is_free_product') ? 'error' : '' }}}">
					{{ Form::label('is_free_product', trans("webshoppack::product.free_product"), array('class' => 'col-lg-3 control-label required-icon')) }}
					<div class="col-lg-3">
				        <?php
							$is_free_product = $p_details['is_free_product'];
							if(count(Input::old()))
							{
								$is_free_product = Input::old('is_free_product');
							}
						?>
						<input type="checkbox" name="is_free_product"  id="is_free_product" class="checkbox" value="Yes" @if($is_free_product == 'Yes') checked="checked" @endif>
				        <label class="error">{{{ $errors->first('is_free_product') }}}</label>
					</div>
				</div>
			@endif

			<div class="form-group fn_clsPriceFields {{{ $errors->has('product_price') ? 'error' : '' }}}">
				{{ Form::label('product_price', trans("webshoppack::product.product_price"), array('class' => 'col-lg-3 control-label required-icon')) }}
				<div class="col-lg-2">
			        {{  Form::text('product_price', null, array('class' => 'form-control')); }}
			        <label class="error">{{{ $errors->first('product_price') }}}</label>
				</div>
			</div>

			<div class="form-group fn_clsPriceFields {{{ $errors->has('product_discount_price') ? 'error' : '' }}}">
				{{ Form::label('product_discount_price', trans("webshoppack::product.product_price_after_discount"), array('class' => 'col-lg-3 control-label')) }}
				<div class="col-lg-2">
			        {{  Form::text('product_discount_price', null, array('class' => 'form-control')); }}
			        <label class="error">{{{ $errors->first('product_discount_price') }}}</label>
				</div>
			</div>

			<div class="form-group mem-datepick fn_clsPriceFields {{{ $errors->has('product_discount_fromdate') ? 'error' : '' }}}">
	            {{ Form::label('product_discount_fromdate', trans("webshoppack::product.discount_from_date"), array('class' => 'col-lg-3 control-label')) }}
	            <div class="col-lg-2">
	                {{ Form::text('product_discount_fromdate', null, array('id'=>"product_discount_fromdate", 'class'=>'form-control clsDatePicker', 'maxlength'=>'100')) }}
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-sm btn-info" onclick="$('#product_discount_fromdate').focus()"><i class="icon-calendar"></i></button>
                    </span>
                    <label class="error">{{{ $errors->first('product_discount_fromdate') }}}</label>
	            </div>
	        </div>

	        <div class="form-group mem-datepick fn_clsPriceFields {{{ $errors->has('product_discount_todate') ? 'error' : '' }}}">
	            {{ Form::label('product_discount_todate', trans("webshoppack::product.discount_to_date"), array('class' => 'col-lg-3 control-label')) }}
	            <div class="col-lg-2">
                    {{ Form::text('product_discount_todate', null, array('id'=>"product_discount_todate", 'class'=>'form-control clsDatePicker', 'maxlength'=>'100')) }}
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-sm btn-info" onclick="$('#product_discount_todate').focus()"><i class="icon-calendar"></i></button>
                    </span>
	                <label class="error">{{{ $errors->first('product_discount_todate') }}}</label>
	            </div>
	        </div>

			<div class="form-group">
				{{ Form::hidden('id', $p_id, array('id' => 'id')) }}
				{{ Form::hidden('p', $d_arr['p'], array('id' => 'p')) }}
				<div class="col-lg-offset-3 col-lg-3">
					<button name="edit_product" id="edit_product" value="edit_product" type="submit" class="btn btn-success">{{ trans("webshoppack::product.save_and_proceed") }}</button>
				</div>
			</div>
	    </fieldset>
		{{ Form::close() }}
	@endif
	{{-- Price form end --}}

	{{-- Attribute form start --}}
	@if($d_arr['p'] == 'attribute')
	  {{ Form::model($p_details, [
	                        'url' => $p_url,
	                        'method' => 'post',
	                        'id' => 'addProductAttributefrm', 'class' => 'form-horizontal form-request'
	                        ]) }}
	    <fieldset class="well">
	    	@if(count($d_arr['attr_arr']) > 0)
	    		@foreach($d_arr['attr_arr'] AS $attr)
	    			<?php
	    			$elem_name = 'attribute_' .$attr['attribute_id'];
	    			$required_class = '';

					$validation =  explode("|", $attr['validation_rules']);
					if (in_array('required', $validation)) {
						$required_class = 'required-icon';
					}
					$elem_value = $attr['default_value'];

	    			if(count(Input::old()) > 0){
						$elem_value = Input::old($elem_name);
					}

	    			?>
	    			<div class="form-group fn_clsPriceFields {{{ $errors->has($elem_name) ? 'error' : '' }}}">
						{{ Form::label($elem_name, ucfirst($attr['attribute_label']), array('class' => 'col-lg-3 control-label '.$required_class)) }}
						<div class="col-lg-3">
							<?php
								$option_arr = $service_obj->getAttributeOptions($attr['attribute_id']);
								$input_type = $attr['attribute_question_type'];
							?>
							@if($input_type == 'text')
								{{ Form::text($elem_name, $elem_value, array('class' => 'form-control')) }}
							@elseif($input_type == 'textarea')
								{{ Form::textarea($elem_name, null, array('class' => 'form-control fn_editor')) }}
							@elseif($input_type == 'option')
								<ul class="list-unstyled">
									@foreach($option_arr AS $key => $val)
										<?php
										$id_name = 'attribmopt_'.strtolower(str_replace(' ', '_', $val));
										$checked = '';
										if (is_array($elem_value)) {
											$checked = in_array($key, $elem_value) ? ' checked ':'';
										}
										else{
											$checked = ($key == $elem_value) ? ' checked ':'';
										}
										?>
										<li class="radio"><input type="radio" name="{{ $elem_name }}" id="{{ $id_name }}" value="{{ $key }}" {{ $checked }}>
										{{ Form::label($id_name, $val, array('class' => 'control-label')) }}</li>
									@endforeach
								</ul>
							@elseif($input_type == 'check')
								<ul class="list-unstyled">
									@foreach($option_arr as $key => $val)
										<?php
										if (is_array($elem_value)) {
											$checked = in_array($key, $elem_value) ? ' checked="checked" ':'';
										} else {
											$checked = '';
										}
										$id_name = 'attribmopt_'.strtolower(str_replace(' ', '_', $val));
										?>
									<li class="checkbox"><input type="checkbox" id="{{ $id_name }}" name="{{ $elem_name.'[]' }}" value="{{ $key }}" {{ $checked }}>
									{{ Form::label($id_name, $val, array('class' => 'control-label')) }}</li>
									@endforeach
								</ul>
							@elseif($input_type == 'select')
								<select name="{{ $elem_name }}" class="control-label selectpicker-2">
									<option value="">{{ trans('common.select_option') }}</option>
									@foreach($option_arr as $key => $val)
										<?php
										if (is_array($elem_value)) {
											$selected = in_array($key, $elem_value) ? ' selected="selected" ':'';
										} else {
											$selected = ($key == $elem_value) ? ' selected="selected" ':'';
										}
										?>
										<option value="{{ $key }}" {{ $selected }}>{{ $val }}</option>
									@endforeach
								</select>
							@elseif($input_type == 'multiselectlist')
								<select name="{{ $elem_name.'[]' }}" multiple class="control-label selectpicker-2">
									<option value="">{{ trans('common.select_option') }}</option>
									@foreach($option_arr as $key => $val)
										<?php
										if (is_array($elem_value)) {
											$selected = in_array($key, $elem_value) ? ' selected="selected" ':'';
										} else {
											$selected = ($key == $elem_value) ? ' selected="selected" ':'';
										}
										?>
										<option value="{{ $key }}" {{ $selected }}>{{ $val }}</option>
									@endforeach
								</select>
							@endif
							<label class="error">{{{ $errors->first($elem_name) }}}</label>
						</div>
					</div>
	    		@endforeach

				<div class="form-group">
					{{ Form::hidden('id', $p_id, array('id' => 'id')) }}
					{{ Form::hidden('p', $d_arr['p'], array('id' => 'p')) }}
					{{ Form::hidden('product_category_id', $p_details['product_category_id'], array('id' => 'product_category_id')) }}
					<div class="col-lg-offset-3 col-lg-5">
						<button name="edit_product" id="edit_product" value="edit_product" type="submit" class="btn btn-success">{{ trans("webshoppack::product.save_and_proceed") }}</button>
					</div>
				</div>
			@endif
	    </fieldset>
		{{ Form::close() }}
	@endif
	{{-- Attribute form end --}}

	{{-- Preview files form start --}}
	@if($d_arr['p'] == 'preview_files')
		{{ Form::model($d_arr['p_img_arr'], [
	                        'url' => $p_url,
	                        'method' => 'post',
	                        'id' => 'addProductPreviewFilesfrm', 'class' => 'form-horizontal form-request', 'files' => true,
	                        ]) }}
		    <fieldset class="well">
            	<div class="form-group">
                    <label class="col-lg-3 control-label">{{ trans("webshoppack::product.products_thumb_image_details") }}<strong>( {{ Config::get('webshoppack::photos_thumb_width') }} X {{ Config::get('webshoppack::photos_thumb_height') }} )</strong></label>
                    <div class="col-lg-3">
						<?php
                            $p_default_img = $service_obj->getProductDefaultThumbImage($p_id, 'default', $d_arr['p_img_arr']);
                            $p_thumb_img = $service_obj->getProductDefaultThumbImage($p_id, 'thumb', $d_arr['p_img_arr']);
                        ?>
                        <img id="item_thumb_image_id" src="{{$p_thumb_img['image_url']}}" @if(isset($p_thumb_img["thumbnail_width"])) width='{{$p_thumb_img["thumbnail_width"]}}' height='{{$p_thumb_img["thumbnail_height"]}}' @endif title="{{ $p_thumb_img['title']  }}" alt="{{ $p_thumb_img['title']  }}">
                    </div>
                </div>

				<div class="form-group {{{ $errors->has('thumbnail_title') ? 'error' : '' }}}">
					{{ Form::label('thumbnail_title', trans("webshoppack::product.thumbnail_title"), array('class' => 'col-lg-3 control-label')) }}
					<div class="col-lg-3">
				        {{ Form::text('thumbnail_title', null, array('class' => 'form-control', 'id' => 'item_thumb_image_title', 'onkeypress' => "javascript:editItemImageTitle($p_id, 'thumb');", 'onblur' => "return false; saveProductImageTitle($p_id, 'thumb', true);")); }}
				        <span id="item_thumb_edit_span">
								<a onclick="javascript:editItemImageTitle({{ $p_id }}, 'thumb');" href="javascript: void(0);" class="text-info">{{ trans("webshoppack::product.products_edit_resource_title") }}</a> <br /></span>

								<span style="display:none;" id="item_thumb_image_save_span"><a onclick="javascript:saveProductImageTitle({{ $p_id }}, 'thumb', false);" href="javascript: void(0);" class="text-success">{{ trans("webshoppack::product.products_save_resource_title") }}</a><br /></span>
				        <label class="error">{{{ $errors->first('thumbnail_title') }}}</label>
					</div>
				</div>

				<div class="form-group {{{ $errors->has('upload_thumb') ? 'error' : '' }}}">
					{{ Form::label('upload_thumb', trans("webshoppack::product.upload_thumb"), array('class' => 'col-lg-3 control-label')) }}
					<div class="col-lg-3">
						<div class="btn btn-info" id="upload_thumb"> <span>{{ trans("webshoppack::product.products_upload_thumb_image") }}</span></div>
				        <p @if($p_thumb_img['no_image']) style="display:none;"@endif id="link_remove_thumb_image" class="mt7 mb0">
							<a onclick="javascript:removeProductThumbImage({{ $p_id }});" href="javascript: void(0);" class="text-danger">{{ trans("webshoppack::product.remove_image") }}</a>
						</p>
						<div class="pull-left image-formatdet mt7">
                            <i class="fa fa-question-circle pull-left"></i>
                            <p><small>{{ str_replace("\n",'<br />',sprintf(trans("webshoppack::product.products_thumb_allowed_image_formats_size"), implode(',', Config::get("webshoppack::thumb_format_arr")) , Config::get("webshoppack::thumb_max_size"), trans("webshoppack::product.file_size_in_MB"))) }}</small></p>
                        </div>
				        <label class="error">{{{ $errors->first('upload_thumb') }}}</label>
					</div>
				</div>

                <div class="form-group">
                    <label class="col-lg-3 control-label">{{ trans("webshoppack::product.products_default_image_details") }}<strong>( {{ Config::get('webshoppack::photos_large_width') }} X {{ Config::get('webshoppack::photos_large_height') }} )</strong></label>
					<div class="col-lg-3 img-width100">
						<img id="item_default_image_id" src="{{$p_default_img['image_url']}}" @if(isset($p_default_img["default_width"])) width='{{$p_default_img["default_width"]}}' height='{{$p_default_img["default_height"]}}' @endif title="{{ $p_default_img['title']  }}" alt="{{ $p_default_img['title']  }}" class="">
                    </div>
                </div>

				<div class="form-group {{{ $errors->has('default_title') ? 'error' : '' }}}">
					{{ Form::label('default_title', trans("webshoppack::product.default_title"), array('class' => 'col-lg-3 control-label')) }}
					<div class="col-lg-3">
				        {{  Form::text('default_title', null, array('class' => 'form-control',  'id' => 'item_default_image_title', 'onkeypress' => "javascript:editItemImageTitle($p_id, 'default');", 'onblur' => "return false; saveProductImageTitle($p_id, 'default', true);")); }}
				        <span id="item_default_edit_span">
                            <a onclick="javascript:editItemImageTitle({{ $p_id }}, 'default');" href="javascript: void(0);" class="text-info">{{ trans("webshoppack::product.products_edit_resource_title") }}</a> <br /></span>

                            <span  style="display:none;" id="item_default_image_save_span"><a onclick="javascript:saveProductImageTitle({{ $p_id }}, 'default', false);" href="javascript: void(0);" class="text-success">{{ trans("webshoppack::product.products_save_resource_title") }}</a><br /></span>
				        <label class="error">{{{ $errors->first('default_title') }}}</label>
					</div>
				</div>

				<div class="form-group {{{ $errors->has('upload_default') ? 'error' : '' }}}">
					{{ Form::label('upload_default', trans("webshoppack::product.upload_default"), array('class' => 'col-lg-3 control-label')) }}
					<div class="col-lg-3">
				        <div class="btn btn-info" id="upload_default"> <span>{{ trans("webshoppack::product.products_upload_default_image") }}</span></div>
				        <p class="clsPreviewImageRemove mt7 mb0" @if($p_default_img['no_image']) style="display:none;"@endif id="link_remove_default_image">
							<a onclick="javascript:removeItemDefaultImage({{ $p_id }});" href="javascript: void(0);" class="text-danger">{{ trans("webshoppack::product.remove_image") }}</a>
						</p>
                        <div class="pull-left image-formatdet mt7">
                            <i class="fa fa-question-circle pull-left"></i>
                            <p><small>{{ str_replace("\n",'<br />',sprintf(trans("webshoppack::product.products_default_allowed_image_formats_size"), implode(',', Config::get("webshoppack::default_format_arr")) , Config::get("webshoppack::default_max_size"), trans("webshoppack::product.file_size_in_MB"))) }}</small></p>
                        </div>
				        <label class="error">{{{ $errors->first('upload_default') }}}</label>
					</div>
				</div>

				<h3 class="title-two">{{ trans("webshoppack::product.products_uploaded_files") }}</h3>
				<div class="form-group">
					<p>{{ str_replace("\n",'<br />',sprintf(trans("webshoppack::product.products_preview_allowed_file_formats_size"), implode(',', Config::get("webshoppack::preview_format_arr")) , Config::get("webshoppack::preview_max_size"), trans("webshoppack::product.file_size_in_MB"), Config::get("webshoppack::preview_max"))) }}</p>

					<table summary="" id="resourcednd" class="fn_ItemResourceImageListTable table table-bordered">
	                    <tbody class="fn_formBuilderListBody" id="preview_tbl_body">
	                        <tr class="nodrag nodrop" id="ItemResourceTblHeader">
	                            <th class="col-lg-2">{{ trans("webshoppack::product.file_title") }}</th>
	                            <th>{{ trans("webshoppack::product.title") }}</th>
	                            <th class="col-lg-1">{{ trans("webshoppack::common.action") }}</th>
	                        </tr>
	                        <?php
	                        	$resources_arr = $d_arr['resources_arr'];
	                        ?>
	                        @foreach($resources_arr AS $inc => $value)
	                        <tr id="itemResourceRow_{{ $resources_arr[$inc]['resource_id'] }}" class="formBuilderRow">
	                            <td>
	                                @if($resources_arr[$inc]['resource_type'] == 'Image')
	                                   <a href="#"><img src="{{ URL::asset(Config::get("webshoppack::photos_folder")) .'/'. $resources_arr[$inc]['filename_thumb'] }}" alt="{{ $resources_arr[$inc]['title'] }}"
	                                   {{ Agriya\Webshoppack\CUtil::DISP_IMAGE(74, 74, $resources_arr[$inc]['t_width'], $resources_arr[$inc]['t_height']) }} /></a>
	                                @endif
	                            </td>
	                            <td>
	                                <input class="form-control" type="text" name="item_resource_image_{{ $resources_arr[$inc]['resource_id'] }}" id="resource_title_field_{{ $resources_arr[$inc]['resource_id'] }}" value="{{ $resources_arr[$inc]['title'] }}" onkeypress="javascript:editItemResourceTitle({{ $resources_arr[$inc]['resource_id']  }});"  />
	                                <p class="clsUploadfileTitleList" style="display:none;" id="resource_title_text_{{ $resources_arr[$inc]['resource_id'] }}">{{ $resources_arr[$inc]['title'] }}</p>
	                                <span id="item_resource_edit_span_{{ $resources_arr[$inc]['resource_id'] }}"><a onclick="javascript:editItemResourceTitle({{ $resources_arr[$inc]['resource_id'] }});" href="javascript: void(0);" class="text-info">{{ trans("webshoppack::product.products_edit_resource_title") }}</a> <br /></span>
	                                <span  style="display:none;" id="item_resource_save_span_{{ $resources_arr[$inc]['resource_id'] }}"><a onclick="javascript:saveItemResourceTitle({{ $resources_arr[$inc]['resource_id'] }});" href="javascript: void(0);" class="text-success">{{ trans("webshoppack::product.products_save_resource_title") }}</a><br /></span>
	                            </td>
	                            <td>
	                                <a onclick="javascript:removeItemResourceRow({{ $resources_arr[$inc]['resource_id'] }});" href="javascript: void(0);" title="Delete" class="text-danger"><i class="fa fa-trash-o"></i></a>
	                            </td>

	                         </tr>
	                        @endforeach
	                    </tbody>
	                </table>
				</div>

                <div class="pull-right">
                    {{ Form::hidden('id', $p_id, array('id' => 'id')) }}
                    {{ Form::hidden('p', $d_arr['p'], array('id' => 'p')) }}
                    <button name="edit_product" id="edit_product" value="edit_product" type="submit" class="btn btn-success">{{ trans("webshoppack::product.products_proceed_next") }}</button>
                </div>

				<div class="form-group {{{ $errors->has('upload_default') ? 'error' : '' }}}">
                    <!-- Upload Button, use any id you wish-->
                    <div class="btn btn-info fn_ItemUploadResourceFileButton col-lg-1" id="upload"> <span>{{ trans("webshoppack::product.products_upload_item_file") }}</span></div>
                    <span id="status" ></span>
                    <ul id="files"></ul>
	            </div>
		    </fieldset>
		{{ Form::close() }}
	@endif
	{{-- Preview files form end --}}

	{{-- Download files form start --}}
	@if($d_arr['p'] == 'download_files')
		<?php
	    	$resources_arr = $d_arr['resources_arr'];
	    ?>
		{{ Form::open(array('id'=>'addProductDownloadFilesfrm', 'method'=>'post','class' => 'form-horizontal form-request', 'url' => $p_url )) }}
        <fieldset class="well">
            <div class="form-group upload-info">
                <div class="mb0">
                    @if(Config::get('webshoppack::download_files_is_mandatory'))<span class="text-danger pull-left">*</span> @endif
                    <p class="pull-left">{{ str_replace("\n",'<br />',sprintf(trans("webshoppack::product.products_download_file_upload_description"), implode(',', Config::get("webshoppack::download_format_arr")) , Config::get("webshoppack::download_max_size"), trans("webshoppack::product.file_size_in_MB"))) }}</p>
                </div>
            </div>

            <div class="form-group {{{ $errors->has('upload_file') ? 'error' : '' }}}">
                <div class="btn btn-info clsItemUploadResourceFileButton col-lg-1" id="upload" @if(count($d_arr['resources_arr']) > 0) style="display:none" @endif><span>{{ trans("webshoppack::product.products_upload_item_file") }}</span></div>
                <label class="error">{{{ $errors->first('upload_file') }}}</label>
            </div>

            <span id="status"></span>
            <ul id="files"></ul>
            <div class="clsPreviewUploadFiles">
                <table summary="" class="table table-bordered">
                    <tbody class="formBuilderListBody">
                        <tr class="nodrag nodrop" id="ItemResourceTblHeader">
                            <th>{{ trans("webshoppack::product.file_title") }}</th>
                            <th>{{ trans("webshoppack::product.title") }}</th>
                            <th>{{ trans("webshoppack::common.action") }}</th>
                        </tr>
                        @foreach($resources_arr AS $inc => $value)
                            <tr id="itemResourceRow_{{ $resources_arr[$inc]['resource_id'] }}" class="formBuilderRow">
                                <td class="col-lg-2">
                                    <strong><a href="{{ $resources_arr[$inc]['download_url'] }}" class="light-link">{{ $resources_arr[$inc]['download_filename'] }}</a></strong>
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="item_resource_image_{{ $resources_arr[$inc]['resource_id'] }}" id="resource_title_field_{{ $resources_arr[$inc]['resource_id'] }}" value="{{ $resources_arr[$inc]['title'] }}"  onkeypress="javascript:editItemResourceTitle({{ $resources_arr[$inc]['resource_id'] }});" />
                                    <span id="item_resource_edit_span_{{ $resources_arr[$inc]['resource_id'] }}"><a onclick="javascript:editItemResourceTitle({{ $resources_arr[$inc]['resource_id'] }});" href="javascript: void(0);" class="text-info">{{ trans("webshoppack::product.products_edit_resource_title") }}</a> <br /></span>
                                    <span  style="display:none;" id="item_resource_save_span_{{ $resources_arr[$inc]['resource_id'] }}"><a onclick="javascript:saveItemResourceTitle({{ $resources_arr[$inc]['resource_id'] }});" href="javascript: void(0);" class="text-success">{{ trans("webshoppack::product.products_save_resource_title") }}</a><br /></span>
                                </td>
                                <td class="col-lg-1">
                                    <a onclick="javascript:removeDownloadItemResourceRow({{ $resources_arr[$inc]['resource_id'] }});" href="javascript: void(0);" title="Delete" class="text-danger"><i class="fa fa-trash-o"></i></a>
                                </td>
                             </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="pull-right">
                    {{ Form::hidden('id', $p_id, array('id' => 'id')) }}
                    {{ Form::hidden('p', $d_arr['p'], array('id' => 'p')) }}
                    <button name="edit_product" id="edit_product" value="edit_product" type="submit" class="btn btn-success">{{ trans("webshoppack::product.products_proceed_next") }}</button>
                </div>
            </div>
        </fieldset>
		{{ Form::close() }}
	@endif
	{{-- Download files form end --}}

	{{-- Publish files form start --}}
	@if($d_arr['p'] == 'publish')
        {{ Form::open(array('id'=>'addProductPublishfrm', 'method'=>'post','class' => 'form-horizontal form-request', 'url' => $p_url )) }}
            <fieldset class="well">
                <div class="form-group {{{ $errors->has('delivery_days') ? 'error' : '' }}}">
                    {{ Form::label('delivery_days', trans("webshoppack::product.delivery_days"), array('class' => 'col-lg-3 control-label')) }}
                    <div class="col-lg-3">
                        {{  Form::text('delivery_days', Input::get('delivery_days', $p_details['delivery_days']), array('class' => 'form-control')); }}
                        <p class="text-muted">{{ trans("webshoppack::product.delivery_days_help") }}</p>
                        <label class="error">{{{ $errors->first('delivery_days') }}}</label>
                    </div>
                </div>

                <div class="form-group {{{ $errors->has('product_notes') ? 'error' : '' }}}">
                	<div class="clearfix">
                        {{ Form::label('product_notes', trans("webshoppack::product.notes_to_admin"), array('class' => 'col-lg-3 control-label')) }}
                        <div class="col-lg-5 custom-textarea">
                            {{  Form::textarea('product_notes', Input::get('product_notes', ''), array('class' => 'form-control')); }}
                            <label class="error">{{{ $errors->first('product_notes') }}}</label>
                        </div>
                    </div>
                </div>
                @if(count($d_arr['product_notes']) > 0)
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-9">
                            <strong class="show"><a href="javascript:void(0);" onclick="javascript:return showHideNotesBlock();" id="showNotes" class="light-link">{{ trans("webshoppack::product.products_show_product_notes") }}</a></strong>
                            <div id="sel_NotesBlock" class="mt10" style="display:none;">
                                @foreach($d_arr['product_notes'] AS $notes)
                                    <?php
                                        $lang = trans("webshoppack::product.user_notes_title");
                                        if($notes->added_by == 'Staff')
                                        {
                                            $lang = trans("webshoppack::product.staff_notes_title");
                                        }
                                        elseif($notes->added_by == 'Admin')
                                        {
                                            $lang = trans("webshoppack::product.admin_notes_title");
                                        }
                                        $title = str_replace('VAR_DATE', date('M d, Y', strtotime($notes->date_added)), $lang);
                                    ?>
                                    <p><strong>{{ $title }}</strong></p>
                                    <p>{{ nl2br(e($notes->notes)) }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    {{ Form::hidden('id', $p_id, array('id' => 'id')) }}
                    {{ Form::hidden('p', $d_arr['p'], array('id' => 'p')) }}
                    <div class="col-lg-offset-3 col-lg-5">
                        @if($p_details['product_status'] != 'Ok' && Config::get('webshoppack::auto_approve'))
                            <button name="edit_product" id="edit_product" value="publish" type="submit" class="btn btn-success">{{ trans("webshoppack::product.products_product_publish") }}</button>
                        @elseif($p_details['product_status'] != 'Ok' && $p_details['product_status'] != 'ToActivate')
                            <button name="edit_product" id="edit_product" value="send_for_approval" type="submit" class="btn btn-success">{{ trans("webshoppack::product.products_product_send_for_approval") }}</button>
                        @else
                            <button name="edit_product" id="edit_product" value="update" type="submit" class="btn btn-success">{{ trans("webshoppack::common.update") }}</button>
                        @endif
                        @if($p_details['product_status'] != 'Draft')
                            <button name="edit_product" id="edit_product" value="set_to_draft" type="submit" class="btn btn-info">{{ trans("webshoppack::product.product_set_to_draft") }}</button>
                        @endif
                    </div>
                </div>
            </fieldset>
        {{ Form::close() }}
	@endif
	{{-- Publish files form end --}}

	<small id="fn_dialog_confirm_msg" class="confirm-delete" style="display:none;"></small>
	<div id="dialog-delete-confirm" class="confirm-dialog-delete" title="" style="display:none;">
	      <p><span class="ui-icon ui-icon-alert"></span><small>{{  trans('webshoppack::product.uploader_confirm_delete') }}</small></p>
	</div>
	<div id="dialog-upload-errors" class="confirm-dialog-delete" title="" style="display:none;">
		<span class="ui-icon ui-icon-alert"></span><span id="dialog-upload-errors-span"></span>
	</div>
@endif
</div>
@stop
@section('script_content')
	@if($d_arr['p'] == 'basic')
		<script src="{{ URL::asset('packages/agriya/webshoppack/js/lib/jquery.inputlimiter.js') }}"></script>
	@endif
	@if($d_arr['p'] == 'preview_files')
		<script src="{{ URL::asset('packages/agriya/webshoppack/js/lib/jQuery_plugins/jquery.tablednd_0_5.js') }}"></script>
	@endif
	@if($d_arr['p'] == 'preview_files' || $d_arr['p'] == 'download_files')
		<script src="{{ URL::asset('packages/agriya/webshoppack/js/uploadDocument.js') }}"></script>
	    <script src="{{ URL::asset('packages/agriya/webshoppack/js/ajaxupload.3.5.min.js') }}"></script>
    @endif
	<script language="javascript" type="text/javascript">
	var mes_required = "{{trans('webshoppack::common.required')}}";
	var product_actions_url = '{{ URL::action('Agriya\Webshoppack\ProductAddController@postProductActions')}}';
	var product_id = '{{ $p_id }}';
	//var cfg_site_name = "{{ Config::get('site.site_name') }}" ;
	var cfg_site_name = "Webshop" ;
	@if($d_arr['p'] == 'basic')
		var root_category_id = "{{ $d_arr['root_category_id'] }}";
		$("#addProductBasicfrm").validate({
			rules: {
				product_name: {
					required: true,
					minlength: "{{Config::get('webshoppack::title_min_length')}}",
	               	maxlength: "{{Config::get('webshoppack::title_max_length')}}"
				},
				product_tags: {
					required: true,
				},
				product_category_id: {
					required: true,
				},
				product_highlight_text: {
					maxlength: "{{Config::get('webshoppack::summary_max_length')}}"
				},
				demo_url: {
					url:true
				}

			},
			messages: {
				product_name: {
					required: mes_required,
					minlength: jQuery.format("{{trans('webshoppack::product.title_min_length')}}"),
	                maxlength: jQuery.format("{{trans('webshoppack::product.title_max_length')}}")
				},
				product_tags: {
					required: mes_required
				},
				product_category_id: {
					required: mes_required
				},
				product_highlight_text: {
					maxlength: jQuery.format("{{trans('webshoppack::product.summary_max_length')}}")
				}
			}
		});

		tinymce.init({
			menubar: "tools",
			selector: "textarea.fn_editor",
			mode : "exact",
			elements: "product_description",
			removed_menuitems: 'newdocument',
			apply_source_formatting : true,
			remove_linebreaks: false,
			height : 400,
			plugins: [
				"advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime media table contextmenu paste emoticons jbimages"
			],
			toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | emoticons",
			relative_urls: false,
			remove_script_host: false
		});

		$('.fn_addSection').click(function() {
			$('#sel_addSection').fadeIn();
			return false;
		});

		$('.fn_saveSectionCancel').click(function() {
			$('#section_name').val('');
			$('.fn_sectionErr').text('');
			$('#sel_addSection').fadeOut();
			return false;
		});

		$('.fn_saveSection').click(function(){
			var section_val = $("#section_name").val();
			if (section_val.trim() == '') {
				$('.fn_sectionErr').html('{{ trans('webshoppack::common.required') }}');
				$('#section_name').focus();
				return false;
			}
			displayLoadingImage(true);

			$.post('{{ \URL::action('Agriya\Webshoppack\ProductAddController@postAddSectionName') }}', { section_name: section_val},  function(response)
			{
				data = eval( '(' +  response + ')');
				//console.log('data' + data);
				if (data.status == 'success') {
					$('#section_name').val('');

					$('.fn_saveSectionCancel').trigger('click');
					$('#user_section_id').append( new Option(data.section_name, data.section_id, true, true) );
					hideLoadingImage(false);
				} else {
					hideLoadingImage(false);
					$('.fn_sectionErr').html(data.error_message);
				}

			});
		});

		var category_list_url = '{{ \URL::action('Agriya\Webshoppack\ProductAddController@getProductSubCategories')}}';
		var listSubCategories = function ()
		{
			select_btn_id = arguments[0];	/* selected drop down box id */
			var sel_cat_id = $('#'+select_btn_id).val();	/* selected category id */
			remove_cat_id = parseInt(arguments[1]);	/* catgory id to remove existing list */
			sel_cat_id_class = $('#'+select_btn_id).attr('class');	/* get existing class */
			$('#loading_sub_category').show();	/* display loading text */

			/* get sub category list */
			if (sel_cat_id != '')
			{
				$.get(category_list_url + '?action=get_product_sub_categories&category_id=' + sel_cat_id,{},function(data)
				{
					data_arr = data.split('~~~');	/* contains new drop down element & new category id with top level categories */
					data = data_arr[0];	/* assigned new drop down element */

					existing_sel_ids = $('#my_selected_categories').val();
					existing_sel_ids_arr = existing_sel_ids.split(',');
					existing_sel_ids_length = existing_sel_ids_arr.length;
					for (var i=0;i<existing_sel_ids_length;i++)
					{
						if( parseInt(existing_sel_ids_arr[i]) > remove_cat_id){
							$('.fn_subCat_'+existing_sel_ids_arr[i]).remove()
						}
					}
					$('.fn_clsNoSubCategryFound').hide();
					/* add new sub categories list */
					$('#sub_categories').append(data);

					/* assign new hidden values */
					$('#my_selected_categories').val(data_arr[1]); /* assign new categories list */
					$('#my_category_id').val(sel_cat_id);	/* update category id hidden value */

					//$('#sub_category_'+sel_cat_id).text("");	/* change css class */
					$('#sub_category_'+sel_cat_id).addClass(sel_cat_id_class+' subCat_'+remove_cat_id)	/* change css class */
					$('#loading_sub_category').hide();	/* hide loading text */

				});
			}
			else
			{
				$.get(category_list_url + '?action=get_product_sub_categories&category_id=' + remove_cat_id,{},function(data)
				{
					data_arr = data.split('~~~');	/* contains new drop down element & new category id with top level categories */
					new_categories = data_arr[1];	/* assigned new categories */

					existing_sel_ids = $('#my_selected_categories').val();
					existing_sel_ids_arr = existing_sel_ids.split(',');
					existing_sel_ids_length = existing_sel_ids_arr.length;
					for (var i=0;i<existing_sel_ids_length;i++)
					{
						if( parseInt(existing_sel_ids_arr[i]) > remove_cat_id){
							$('.fn_subCat_'+existing_sel_ids_arr[i]).remove()
						}
					}
					/* assign new hidden values */
					$('#my_selected_categories').val(new_categories); /* assign new categories list */
					/* update category id hidden value */
					if(root_category_id != remove_cat_id)
					{
						$('#my_category_id').val(remove_cat_id);
					}
					else
					{
						$('#my_category_id').val('');
					}

				});
				$('#loading_sub_category').hide();
			}
		};
		setInputLimiterById('product_highlight_text', {{ Config::get('webshoppack::summary_max_length') }});

		function setInputLimiterById(ident, char_limit)	{
			if ($('#'+ident).length > 0){
				$('#'+ident).inputlimiter({
					limit: char_limit,
					remText: '{{ trans("webshoppack::common.words_remaining_1")}} %n {{ trans("webshoppack::common.words_remaining_2")}} %s {{ trans("webshoppack::common.words_remaining_3")}}',
					limitText: '{{ trans("webshoppack::common.limitted_words_1")}} %n {{ trans("webshoppack::common.limitted_words_2")}}%s'
				});
			}
		}
	@endif
	@if($d_arr['p'] == 'price')
		$(document).ready(function(){
			@if(Config::get("webshoppack::can_upload_free_product"))
				if ($('#is_free_product').attr('checked'))
				{
			    	showPriceFields(false);
			    } else {
			    	showPriceFields(true);
			    }

				$('#is_free_product').click(function() {
					if (this.checked) {
						showPriceFields(false);
					} else {
						showPriceFields(true);
					}
				});
			@endif

		});
		function showPriceFields(flag)
		{
			if (flag) {
				$('.fn_clsPriceFields').show();
			}
			else
			{
				$('.fn_clsPriceFields').hide();
			}
		}

		$(function() {
			$('#product_discount_fromdate').datepicker({
				format: 'dd/mm/yyyy'
			});
			$('#product_discount_todate').datepicker({
				format: 'dd/mm/yyyy'
			});
		});
	@endif
	@if($d_arr['p'] == 'preview_files')
		var prev_max_upload = parseInt('{{ Config::get('webshoppack::preview_max') }}');
		function editItemImageTitle(product_id, type) {
			if (!confirmItemChange()) {
				return false;
			}
			$('#item_' + type + '_image_title').show();
			$('#item_' + type + '_edit_span').hide();
			$('#item_' + type + '_image_save_span').show();
			$('#item_' + type + '_image_title').focus();
		}


		function saveProductImageTitle(product_id, type, no_process_dialog)
		{
			var image_title = $('#item_'+ type +'_image_title').val();
			postData = 'action=save_product_' + type + '_image_title&product_image_title=' + image_title +'&product_id=' + product_id;
			if (!no_process_dialog)
				displayLoadingImage (true);

			$.post(product_actions_url, postData,  function(data)
			{
				if (data == 'success') {
					$('#item_' + type + '_edit_span').show();
					$('#item_' + type + '_image_save_span').hide();
				} else {
					showErrorDialog({status: 'error', error_message: '{{  trans("webshoppack::product.not_completed") }}'});
				}
				hideLoadingImage (false);

			});
		}

		$(function(){
			var btnUpload=$('#upload_thumb');
			new AjaxUpload(btnUpload, {
				action: product_actions_url,
				name: 'uploadfile',
				data: ({action: 'upload_product_thumb_image',product_id : product_id, upload_tab: 'preview'}),
				method: 'POST',
				onSubmit: function(file, ext){
					if (!confirmItemChange()) {
						return false;
					}

					 if (!(ext && /^({{ implode('|', Config::get("webshoppack::thumb_format_arr")) }})$/.test(ext))){
						showErrorDialog({status: 'error', error_message: '{{ sprintf(trans("webshoppack::product.products_allowed_formats"), implode(',', Config::get("webshoppack::thumb_format_arr"))) }}'});
						return false;
					}

					var settings = this._settings;
					settings.data.item_image_title = $.trim($('#item_thumb_image_title').val());

					displayLoadingImage(true);
				},
				onComplete: function(file, response){
					//console.info(response); hideLoadingImage (false);
					data = eval( '(' +  response + ')');
					hideLoadingImage(false);
					if(data.status=="success"){
						$('#item_thumb_image_id').attr('src',data.server_url + '/'+ data.filename);
						if (data.t_width == '') {
							$('#item_thumb_image_id').removeAttr('width');
						} else {
							$('#item_thumb_image_id').attr('width',data.t_width)
						}

						if (data.t_height == '') {
							$('#item_thumb_image_id').removeAttr('height');
						} else {
							$('#item_thumb_image_id').attr('height',data.t_height)
						}

						if ($('#item_thumb_image_title').val() == '') {
							$('#item_thumb_image_title').val(data.title);
						}

						$('#item_thumb_image_id').attr('title', $('#item_thumb_image_title').val())
															.attr('alt', $('#item_thumb_image_title').val());
						$('#link_remove_thumb_image').show();
						updateProductStatus();

					} else {
						showErrorDialog(data);
					}
				}
			});
		});

		function removeItemDefaultImage(product_id)
		{
			if (!confirmItemChange()) {
				return false;
			}
			$("#dialog-delete-confirm").dialog({ title: cfg_site_name, modal: true,
					buttons: {
						"{{ trans('webshoppack::common.yes') }}": function() {
							postData = 'action=remove_default_image&product_id=' + product_id;
			displayLoadingImage(true);
			$.post(product_actions_url, postData,  function(data)
			{
				if (data == 'success') {
					$('#item_default_image_id').attr('src', '{{ URL::asset("packages/agriya/webshoppack/images/no_image").'/'.Config::get("webshoppack::photos_large_no_image") }}');
					@if(isset($d_arr['default_no_image']) && $d_arr['default_no_image']['width'] > 0)
						$('#item_default_image_id').attr('width', {{ $d_arr['default_no_image']['width'] }});
					@else
						$('#item_default_image_id').removeAttr('width');
					@endif

					@if(isset($d_arr['default_no_image']) && $d_arr['default_no_image']['height'] > 0)
						$('#item_default_image_id').attr('height', {{ $d_arr['default_no_image']['height'] }});
					@else
						$('#item_default_image_id').removeAttr('height');
					@endif

					$('#item_default_image_id').attr('title', '{{ trans('webshoppack::product.no_image') }}')
												.attr('alt', '{{ trans('webshoppack::product.no_image') }}');
					$('#link_remove_default_image').hide();
					$('#item_default_image_title').val('');
					updateProductStatus();
				} else {
					showErrorDialog({status: 'error', error_message: '{{  trans("webshoppack::product.not_completed") }}'});
				}
				hideLoadingImage(false);

			});
							$(this).dialog("close");

						}, "{{  trans("webshoppack::common.no") }}": function() { $(this).dialog("close"); }
					}
				});
		}

		function removeProductThumbImage(product_id)
		{
			if (!confirmItemChange()) {
						return false;
					}
			$("#dialog-delete-confirm").dialog({ title: cfg_site_name, modal: true,
					buttons: {
						"{{ trans('webshoppack::common.yes') }}": function() {
							postData = 'action=remove_default_thumb_image&product_id=' + product_id;
							displayLoadingImage(true);
							$.post(product_actions_url, postData,  function(data)
							{
								if (data == 'success') {
									$('#item_thumb_image_id').attr('src', '{{ URL::asset("packages/agriya/webshoppack/images/no_image").'/'.Config::get("webshoppack::photos_thumb_no_image") }}');

									@if(isset($d_arr['thumb_no_image']) && $d_arr['thumb_no_image']['width'] > 0)
										$('#item_thumb_image_id').attr('width', {{ $d_arr['thumb_no_image']['width'] }});
									@else
										$('#item_thumb_image_id').removeAttr('width');
									@endif

									@if(isset($d_arr['thumb_no_image']) && $d_arr['thumb_no_image']['height'] > 0)
										$('#item_thumb_image_id').attr('height', {{ $d_arr['thumb_no_image']['height'] }});
									@else
										$('#item_thumb_image_id').removeAttr('height');
									@endif

									$('#link_remove_thumb_image').hide();
									$('#item_thumb_image_id').attr('title', '{{ trans('webshoppack::product.no_image') }}');
									$('#item_thumb_image_title').val('');
									updateProductStatus();
								} else {
									showErrorDialog({status: 'error', error_message: '{{  trans("webshoppack::product.not_completed") }}'});
								}

								hideLoadingImage(false);

							});

							$(this).dialog("close");

						}, "{{  trans("webshoppack::common.no") }}": function() { $(this).dialog("close"); }
					}
				});
		}

		$(function(){
			var btnUpload=$('#upload_default');
			new AjaxUpload(btnUpload, {
				action: product_actions_url,
				name: 'uploadfile',
				data: ({action: 'upload_item_default_image',product_id : product_id, upload_tab: 'preview'}),
				onSubmit: function(file, ext){
					if (!confirmItemChange()) {
						return false;
					}
					if (!(ext && /^({{ implode('|', Config::get("webshoppack::default_format_arr")) }})$/.test(ext))){
						showErrorDialog({status: 'error', error_message: '{{ sprintf(trans("webshoppack::product.products_allowed_formats"), implode(',', Config::get("webshoppack::thumb_format_arr"))) }}'});
						return false;
					}

					var settings = this._settings;
					settings.data.item_image_title = $.trim($('#item_default_image_title').val());
					displayLoadingImage(true);
				},
				onComplete: function(file, response){
					//console.info(response);
					data = eval( '(' +  response + ')');
					hideLoadingImage (false);
					if(data.status=="success"){
						$('#item_default_image_id').attr('src',data.server_url + '/'+ data.filename);

						$('#item_default_image_title_id').html(data.title);

						if ($('#item_default_image_title').val() == '') {
							$('#item_default_image_title').val(data.title);
						}

						$('#item_default_image_id').attr('title',$('#item_default_image_title').val())
															.attr('alt',$('#item_default_image_title').val());

						$('#link_remove_default_image').show();

						updateProductStatus();

					} else{
						showErrorDialog(data);
					}
				}
			});

		});

		$(function(){
			var btnUpload=$('#upload');
			var status=$('#status');
			new AjaxUpload(btnUpload, {
				action: product_actions_url,
				name: 'uploadfile',
				data: ({action: 'upload_resource_preview',product_id : product_id, resource_type: 'image', upload_tab: 'preview'}),
				onSubmit: function(file, ext){
					var resource_type = 'image';
					if (!confirmItemChange()) {
						return false;
					}

					if (!(ext && /^({{ implode('|', Config::get("webshoppack::preview_format_arr")) }})$/.test(ext))){ // e.g. jpg|jpeg|gif etc
						showErrorDialog({status: 'error', error_message: '{{ sprintf(trans("webshoppack::product.products_allowed_formats"), implode(',', Config::get("webshoppack::preview_format_arr"))) }}'});
						return false;
					}

					displayLoadingImage(true);
				},
				onComplete: function(file, response) {
					//console.info(response);
					data = eval( '(' +  response + ')');

					hideLoadingImage (false);
					if(data.status=="success"){
						// files folder come from config media.folder
						$('#files').html('');
						html_text = '<tr  id="itemResourceRow_' + data.resource_id  + '" class="formBuilderRow"><td>';

						if (data.resource_type == 'Image') {
							html_text += '<a href="#"><img width="' + data.t_width + '" height="' + data.t_height +'" src="' +  data.server_url + '/'+ data.filename + '"  alt="' + data.title + '" title="' + data.title + '" /></a>';
						} else {
							html_text += '<p><a href="#">{{ trans("webshoppack::product.products_product_preview_type_unknown")  }}</a></p>';
						}

						html_text +=  '</td><td><input class="form-control" type="text" name="item_resource_image_' + data.resource_id +'" id="resource_title_field_' + data.resource_id +'" value="'+ data.title +'" onkeypress="javascript:editItemResourceTitle(' + data.resource_id +');"  />';
						html_text +=  '<span  style="display:none;" id="item_resource_save_span_' + data.resource_id +'"><a onclick="javascript:saveItemResourceTitle(' + data.resource_id +');" href="javascript: void(0);">{{ trans("webshoppack::product.products_save_resource_title") }}</a><br /></span>';
						html_text += '<span id="item_resource_edit_span_' + data.resource_id +'"><a onclick="javascript:editItemResourceTitle(' + data.resource_id + ');" href="javascript: void(0);">{{ trans("webshoppack::product.products_edit_resource_title") }}</a> <br /></span>';
						html_text += '</td><td><a onclick="javascript:removeItemResourceRow(' + data.resource_id +');" href="javascript: void(0);" class="text-danger"><i class="fa fa-trash-o"></i></a>';
						html_text += '</td></tr>';

						$('.fn_formBuilderListBody').append(html_text);
						$('.fn_ItemResourceImageListTable').tableDnDUpdate();
						initializeRowMouseOver();
						updateProductStatus();
						hideResourceUploadButton('image');

						if ($('#preview_tbl_body').children().length > 1) { $('#id_item_preview').show(); }
						//$(html_text).insertAfter('#ItemResourceTblHeader');
					} else{
						showErrorDialog(data);
					}
				}
			});

		});

		function removeItemResourceRow(resource_id) {
			if (!confirmItemChange()) {
						return false;
					}
		$("#dialog-delete-confirm").dialog({ title: cfg_site_name, modal: true,
				buttons: {
					"{{ trans('webshoppack::common.yes') }}": function() {
						postData = 'action=delete_resource&row_id=' + resource_id + '&product_id='+product_id,
						displayLoadingImage(true);
		$.post(product_actions_url, postData,  function(response)
		{
				hideLoadingImage (false);
				//console.info(data);
				data = eval( '(' +  response + ')');
				if(data.result == 'success')
				{
					$('#itemResourceRow_' + data.row_id).remove();

					if ($('#preview_tbl_body').children().length <= 1) { $('#id_item_preview').hide(); }

					// this should only show when the items is less than allowed
					if ($('.fn_formBuilderListBody').children().length < (prev_max_upload + 1)) {
						$('.fn_ItemUploadResourceFileButton').css('display', 'block');
					}

					updateProductStatus();
				}
				else
				{
					showErrorDialog({status: 'error', error_message: '{{ trans("webshoppack::product.not_completed") }}'});
				}

			});
							$(this).dialog("close");

						}, "{{ trans('webshoppack::common.no') }}": function() { $(this).dialog("close"); }
					}
				});
		}

		// Item status is not changed to Draft since, only ordering changes.
		$(document).ready(function()
		{
			$(".fn_ItemResourceImageListTable").tableDnD({
				onDrop: function(table, row) {
					var postData = 'action=order_resource&' + $.tableDnD.serialize();
					displayLoadingImage(true);
					$.post(product_actions_url, postData,  function(data)
					{
						// mostly no output, only update happens;
						hideLoadingImage (false);

					});
				}
			});

			initializeRowMouseOver();
			hideResourceUploadButton('image');
		});

		function hideResourceUploadButton(resource_type)
		{
			if (resource_type == 'image' && $('.fn_formBuilderListBody').children().length >= ( prev_max_upload + 1)) {
				$('.fn_ItemUploadResourceFileButton').css('display', 'none');
			}
		}
	@endif

	@if($d_arr['p'] == 'download_files')
		$(function() {
			var btnUpload = $('#upload');
			var status = $('#status');
			new AjaxUpload(btnUpload, {
				action: product_actions_url,
				name: 'uploadfile',
				data: ({action: 'upload_resource_file',product_id : product_id, upload_tab: 'download_file'}),
				onSubmit: function(file, ext){
					if (!confirmItemChange()) {
						return false;
					}

					/*if ($('.formBuilderListBody').children().length >= 2) {
						//alert('Only one file can be uploaded');
						//return false;
					}*/
					if (!(ext && /^({{ implode('|', Config::get("webshoppack::download_format_arr")) }})$/.test(ext))){
						showErrorDialog({status: 'error', error_message: '{{ sprintf(trans("webshoppack::product.products_download_file_format_msg"), implode(',', Config::get("webshoppack::download_format_arr"))) }}'});
						return false;
					}
					displayLoadingImage(true);
				},
				onComplete: function(file, response){
					//console.info(response);
					data = eval( '(' +  response + ')');

					hideLoadingImage (false);
					if(data.status=="success"){
						// files folder come from config media.folder
						html_text = '<tr  id="itemResourceRow_' + data.resource_id  + '" class="formBuilderRow"><td>';
						html_text += '<a href="' +  data.download_url + '">'+ data.filename + '</a></td><td>';
						html_text +=  '<input class="form-control" type="text" name="item_resource_image_' + data.resource_id +'" id="resource_title_field_' + data.resource_id +'" value="'+ data.title +'" onkeypress="javascript:editItemResourceTitle(' + data.resource_id +');" />';
						html_text += '<span id="item_resource_edit_span_' + data.resource_id +'"><a onclick="javascript:editItemResourceTitle(' + data.resource_id + ');" href="javascript: void(0);">{{ trans("webshoppack::product.products_edit_resource_title") }}</a> <br /></span>';
						html_text += '<span  style="display:none;" id="item_resource_save_span_' + data.resource_id +'"><a onclick="javascript:saveItemResourceTitle(' + data.resource_id +');" href="javascript: void(0);">{{ trans("webshoppack::product.products_save_resource_title") }}</a><br /></span>';
						html_text += '</td><td><a onclick="javascript:removeDownloadItemResourceRow(' + data.resource_id +');" href="javascript: void(0);" class="text-danger"><i class="fa fa-trash-o"> {{ trans('webshoppack::common.delete') }}</a>';
						html_text += '</td></tr>';
						$('.formBuilderListBody').append(html_text);
						btnUpload.css('display','none');
						updateProductStatus();
					} else{
						showErrorDialog(data);
					}
				}
			});

		});

		function removeDownloadItemResourceRow(resource_id) {
			if (!confirmItemChange()) {
				return false;
			}
			$("#dialog-delete-confirm").dialog({ title: cfg_site_name, modal: true,
				buttons: {
					"{{ trans('webshoppack::common.yes') }}": function() {
						postData = 'action=delete_resource&row_id=' + resource_id + '&product_id='+product_id,
						displayLoadingImage(true);
			$.post(product_actions_url, postData,  function(response)
			{
				hideLoadingImage (false);
				data = eval( '(' +  response + ')');
				if(data.result == 'success')
				{
					$('#itemResourceRow_' + data.row_id).remove();
					if ($('#upload').length > 0) {
						$('#upload').css('display','block');
					}
					updateProductStatus();
				}
				else
				{
					showErrorDialog({status: 'error', error_message: '{{ trans("webshoppack::product.not_completed") }}'});
				}

			});
							$(this).dialog("close");

						}, "{{ trans('webshoppack::common.no') }}": function() { $(this).dialog("close"); }
					}
				});
		}

	@endif
	@if($d_arr['p'] == 'download_files' || $d_arr['p'] == 'preview_files' )
		function editItemResourceTitle(resource_id) {
			if (!confirmItemChange()) {
						return false;
					}
			$('#resource_title_field_' + resource_id).show();
			$('#item_resource_edit_span_' + resource_id).hide();
			$('#item_resource_save_span_' + resource_id).show(); //.addClass('clsSubmitButton');
			$('#resource_title_field_' + resource_id).focus();

			return false;
		}

		function saveItemResourceTitle(resource_id) {
			var resource_title = $('#resource_title_field_' + resource_id).val();
			postData = 'action=save_resource_title&row_id=' + resource_id + '&resource_title=' + 	$('#resource_title_field_' + resource_id).val();
			displayLoadingImage(true);
			$.post(product_actions_url, postData,  function(data)
			{
				if (data == 'success') {
					$('#item_resource_edit_span_' + resource_id).show();
					$('#item_resource_save_span_' + resource_id).hide();// .removeClass('clsSubmitButton');
					updateProductStatus();
				} else {
					showErrorDialog({status: 'error', error_message: '{{  trans("webshoppack::product.not_completed") }}'});
				}
				hideLoadingImage (false);

			});

			return false;
		}
	@endif
	@if($d_arr['p'] == 'publish')
		function showHideNotesBlock()
		{
			if ($('#sel_NotesBlock').is(':visible'))
			{
				$('#showNotes').html("{{  trans("webshoppack::product.products_show_product_notes") }}");
				$('#sel_NotesBlock').hide();
			}
			else
			{
				$('#showNotes').html("{{  trans("webshoppack::product.products_hide_product_notes") }}");
				$('#sel_NotesBlock').show();
			}
		}
	@endif
	function confirmItemChange() {
		return true;
	}

	function updateProductStatus(){
		item_status = 'Draft';
		$('#item_status_text').html(item_status);
		$('#item_current_status').val(item_status);
	}

	$(document).ready(function() {
		$(".custom-menu-toggler").click(function(){
			$(".showpage").slideToggle(500);
		});
	});
	</script>
@stop