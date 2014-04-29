@if (isset($success_message) && $success_message != "")
    <div class="alert alert-success" id="success_msg_div">{{ $success_message }}</div>
@endif
@if (isset($error_message) && $error_message != "")
    <div class="alert alert-success" id="success_msg_div">{{ $error_message }}</div>
@endif
	<div class="alert alert-success" id="success_div" style="display:none;"></div>
{{ Form::model($shop_details, ['url' => URL::to(Config::get('webshoppack::shop_uri').'/users/shop-details'),'method' => 'post','id' => 'shopbanner_frm', 'class' => 'form-horizontal', 'files' => true]) }}
	{{ Form::hidden('submit_form', "update_banner", array("name" => "submit_form", "id" => "submit_form"))}}
	<fieldset>
		<div class="form-group">
			{{-- Form::label('shop_banner_image', trans("webshoppack::shopDetails.shop_banner_image"), array('class' => 'col-sm-2 col-lg-2 control-label')) --}}
			<div class="col-lg-3">
				<div class="pull-left">{{ Form::file('shop_banner_image', array('title' => trans("webshoppack::shopDetails.shop_banner_image"), 'class' => 'btn-info btn-sm')) }}</div>
				<label class="error clearfix pull-left mt5" for="shop_banner_image" generated="true">{{$errors->first('shop_banner_image')}}</label>
				<div class="pull-left mt5">
					<small class="pull-left"><i class="fa fa-question-circle"></i></small>
					<small class="show ml15">
						<span>{{ str_replace("VAR_FILE_FORMAT",  Config::get('webshoppack::shop_uploader_allowed_extensions'), trans('webshoppack::shop.uploader_allowed_upload_format_text')) }}
						</span>
						<span>{{ str_replace("VAR_FILE_MAX_SIZE",  (Config::get('webshoppack::shop_image_uploader_allowed_file_size')/1024).' MB', trans('webshoppack::shop.uploader_allowed_upload_limit')) }}</span>
						<span>{{ str_replace("VAR_IMAGE_RESOLUTION",  Config::get('webshoppack::shop_image_thumb_width').'x'.Config::get('webshoppack::shop_image_thumb_height'), trans('webshoppack::shop.allowed_image_resolution')) }}</span>
					</small>
				</div>
			</div>
		</div>

		@if(count($shop_details) > 0 && $shop_details['image_name'] != '')
			<div class="form-group">
				<div class="col-lg-6">
					<div class="uploaded-image upload-profile clearfix">
						<ul id="uploadedFilesList" class="list-unstyled">
							<li id="itemResourceRow_{{ $shop_details['id'] }}" class="upload-img">
							  <?php $imgPath = URL::asset(Config::get('webshoppack::shop_image_folder')); ?>
							   {{ HTML::image( $imgPath.'/'.$shop_details['image_name'].'_T.'.$shop_details['image_ext'], "", array()); }}
							   <a title="{{trans('webshoppack::common.delete')}}" href="javascript: void(0);" onclick="javascript:removeShopImage({{ $shop_details['id'] }}, '{{$shop_details['image_name']}}', '{{$shop_details['image_ext']}}','shop.shop_image_folder');"><i class="fa fa-times-circle"></i></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		@endif

		<div class="form-group">
			<div class="col-lg-10">
				<button type="button" name="update_banner" class="btn btn-success" id="update_banner" value="update_banner" onclick="javascript:doSubmit('shopbanner_frm', 'banner_details');">{{trans("webshoppack::shopDetails.shop_upload_btn")}}</button>
			</div>
		</div>
	</fieldset>
{{ Form::close() }}