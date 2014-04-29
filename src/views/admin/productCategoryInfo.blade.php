<div id="category_info">
	@if(isset($error_msg) && $error_msg != '')
        <div class="alert alert-danger">{{ $error_msg }}</div>
    @endif
	@if(isset($success_msg) && $success_msg != '')
		<div class="alert alert-success">{{	$success_msg }}</div>
    @endif
	<section>
		{{ Form::model($category_info, [
					'url' => $cat_url,
					'method' => 'post',
					'id' => 'addCategoryfrm', 'class' => 'form-horizontal form-request',
					'files' => true
					]) }}
			{{ Form::hidden('use_all_available_sort_options', 'Yes', array("id" => "use_all_available_sort_options")) }}
			<h4>{{ $d_arr['add_edit_mode_text'] }}</h4>
			<div class="border-type1 search-bar">
				<div class="form-group">
					{{ Form::label('parent_category_name', trans('webshoppack::admin/manageCategory.add-category.parent_category'), array('class' => 'col-sm-2 control-label')) }}
					<div class="col-sm-5 mt5">
						{{ Form::label('parent_category_name', $d_arr['parent_category_name'], array('class' => 'col-xs-10 col-sm-9 valid')) }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('category_name') ? 'error' : '' }}}">
					{{ Form::label('category_name', trans('webshoppack::admin/manageCategory.add-category.category_name'), array('class' => 'col-sm-2 control-label required-icon')) }}
					<div class="col-sm-5">
						{{  Form::text('category_name', Input::get('category_name'), array('id' => 'category_name',  'class' => 'col-xs-10 col-sm-9 valid')); }}
						<label class="error">{{{ $errors->first('category_name') }}}</label>
					</div>
				</div>

				<div class="form-group {{{ $errors->has('seo_category_name') ? 'error' : '' }}}">
					{{ Form::label('seo_category_name', trans('webshoppack::admin/manageCategory.add-category.url_slug'), array('class' => 'col-sm-2 control-label required-icon')) }}
					<div class="col-sm-5">
						{{  Form::text('seo_category_name', Input::get('seo_category_name'), array('id' => 'seo_category_name', 'class' => 'col-xs-10 col-sm-9 valid')); }}
						<label class="error">{{{ $errors->first('seo_category_name') }}}</label>
					</div>
				</div>

				<div class="form-group">
					{{ Form::label('category_image', trans('webshoppack::admin/manageCategory.add-category.category_image'), array('class' => 'col-sm-2 control-label')) }}
					<div class="col-sm-5">
						{{ Form::file('category_image', array()) }}
						<label class="error">{{{ $errors->first('category_image') }}}</label>
						<div class="uploaded-image upload-profile">
							<ul id="uploadedFilesList" class="list-unstyled">
								  @if(count($category_info) > 0)
									@if(isset($category_info['image_name']) && $category_info['image_name'] != '')
										<li id="itemResourceRow_{{ $category_info['id'] }}" class="upload-img">
										  <?php $imgPath = URL::asset(Config::get('webshoppack::product_category_image_folder')); ?>
										   {{ HTML::image( $imgPath.'/'.$category_info['image_name'].'_T.'.$category_info['image_ext'], "", array()); }}
										   <a title="{{trans('webshoppack::common.delete')}}" href="javascript: void(0);" onclick="javascript:removeCategoryImage({{ $category_info['id'] }}, '{{$category_info['image_name']}}', '{{$category_info['image_ext']}}','mp_productCategory.product_category_image_folder');" ><i class="fa fa-times-circle"></i></a>
										</li>
									@endif
								  @endif
							</ul>
						</div>
					</div>
				</div>

				<div class="form-group {{{ $errors->has('category_description') ? 'error' : '' }}}">
					{{ Form::label('category_description', trans('webshoppack::admin/manageCategory.add-category.description'), array('class' => 'col-sm-2 control-label')) }}
					<div class="col-sm-9">
						{{  Form::textarea('category_description', Input::get('category_description'), array('id' => 'category_description', 'class' => 'col-xs-10 col-sm-9 valid')); }}
						<label class="error">{{{ $errors->first('category_description') }}}</label>
					</div>
				</div>

				<div class="form-group {{{ $errors->has('status') ? 'error' : '' }}}">
					{{ Form::label('status', trans('webshoppack::admin/manageCategory.add-category.status'), array('class' => 'col-sm-2 control-label required-icon')) }}
					<div class="col-sm-5 radio-list">
						<label class="radio">
							{{ Form::radio('status', 'active', (Input::get('status') == 'active') ? true : false, array('id' => 'status_active', 'name' => 'status', 'class' => 'ace')) }}
							<span class="lbl">{{ Form::label('status_active', trans('webshoppack::common.active'), array('class' => 'disp-block'))}}</span>
						</label>
						<label class="radio">
							{{ Form::radio('status', 'inactive', (Input::get('status') == 'inactive') ? true : false, array('id' => 'status_inactive', 'name' => 'status', 'class' => 'ace')) }}
							<span class="lbl">{{ Form::label('status_inactive', trans('webshoppack::common.inactive'), array('class' => 'disp-block'))}}</span>
						</label>
						<label class="error">{{{ $errors->first('status') }}}</label>
					</div>
				</div>

			@if($d_arr['edit_form'])
				{{ Form::hidden('is_featured_category', $category_info['is_featured_category'], array("id" => "is_featured_category")) }}
			@endif

				<div class="form-group {{{ $errors->has('category_meta_title') ? 'error' : '' }}}">
					{{ Form::label('category_meta_title', trans('webshoppack::admin/manageCategory.add-category.meta_title'), array('class' => 'col-sm-2 control-label')) }}
					<div class="col-sm-5">
						{{  Form::text('category_meta_title', Input::get('category_meta_title'), array('id' => 'category_meta_title', 'class' => 'col-xs-10 col-sm-9 valid')); }}
						<label class="error">{{{ $errors->first('category_meta_title') }}}</label>
					</div>
				</div>

				<div class="form-group {{{ $errors->has('category_meta_description') ? 'error' : '' }}}">
					{{ Form::label('category_meta_description', trans('webshoppack::admin/manageCategory.add-category.meta_description'), array('class' => 'col-sm-2 control-label')) }}
					<div class="col-sm-9">
						{{  Form::textarea('category_meta_description', Input::get('category_meta_description'), array('id' => 'category_meta_description', 'class' => 'col-xs-10 col-sm-9 valid')); }}
						<label class="error">{{{ $errors->first('category_meta_description') }}}</label>
					</div>
				</div>

				<div class="form-group {{{ $errors->has('category_meta_keyword') ? 'error' : '' }}}">
					{{ Form::label('category_meta_keyword', trans('webshoppack::admin/manageCategory.add-category.meta_Keyword'), array('class' => 'col-sm-2 control-label')) }}
					<div class="col-sm-5">
						{{  Form::text('category_meta_keyword', Input::get('category_meta_keyword'), array('id' => 'category_meta_keyword', 'class' => 'col-xs-10 col-sm-9 valid')); }}
						<label class="error">{{{ $errors->first('category_meta_keyword') }}}</label>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-5">
						{{ Form::hidden('parent_category_id', $d_arr['parent_category_id'], array("id" => "parent_category_id")) }}
						@if($d_arr['edit_form'])
							{{ Form::hidden('category_id', $d_arr['category_id'], array("id" => "category_id")) }}
							<button type="submit" name="category_submit" id="category_submit" class="btn btn-info btn-sm" onclick="doAjaxSubmit('addCategoryfrm', 'category_info');return false;"><i class="icon-ok bigger-110"></i> {{ trans('webshoppack::common.update') }}</button>
							<button type="button" name="cancel_submit" id="cancel_submit" class="btn btn-sm" onclick="addSubCategory($d_arr['root_category_id']);">
							<i class="icon-remove bigger-110"></i> {{ trans('webshoppack::common.cancel') }}</button>
						@else
							<button type="submit" name="category_submit" id="category_submit" class="btn btn-info btn-sm" onclick="doAjaxSubmit('addCategoryfrm', 'category_info');return false;"><i class="icon-ok bigger-110"></i> {{ trans('webshoppack::common.add') }}</button>
						@endif
					</div>
				</div>
			</div>
		{{ Form::close() }}
	</section>
</div>