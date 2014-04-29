@extends(\Config::get('webshoppack::base_view'))
@section('content')
	<h1 class="title-one">{{ trans("webshoppack::shopDetails.title") }}</h1>
	<section class="mt15">
		@if (Session::has('success_message') && Session::get('success_message') != "")
			<div class="alert alert-success">{{	Session::get('success_message') }}</div>
			<?php
				Session::forget('success_message');
			?>
		@endif

		@if(!Agriya\Webshoppack\CUtil::isShopOwner())
		<div class="alert alert-danger">{{ trans("webshoppack::shopDetails.shop_not_set_error_message") }}</div>
		@endif

        {{-- Paypal Details starts here --}}
			<div class="showhide-details title-two">
				<a href="javascript:void(0);" class="fn_clsDropDetails btn-link" onclick="showHidepanels(this, 'paypal_details')">
					{{ trans("webshoppack::shopDetails.shop_paypal_details") }} <i class="customize-fn_show"></i>
				 </a>
			</div>

			<div id="paypal_details" class="clearfix well mb50">
				@include('webshoppack::shopPaypal')
			</div>
		{{-- Paypal Details ends here --}}

		{{-- Shop Details starts here --}}
			<div class="showhide-details title-two">
				<a href="javascript:void(0);" class="fn_clsDropDetails btn-link" onclick="showHidepanels(this, 'shop_details')">
					{{ trans("webshoppack::shopDetails.shop_details") }} <i class="customize-fn_show"></i>
				 </a>
			</div>

			<div id="shop_details" class="clearfix well mb50">
				@include('webshoppack::shopPolicy')
			</div>
		{{-- Shop Details ends here --}}

		{{-- Shop Banner starts here --}}
			<div class="showhide-banner title-two">
				<a href="javascript:void(0);" class="fn_clsDropBanner btn-link" onclick="showHidepanels(this, 'banner_details')">
					{{ trans("webshoppack::shopDetails.shop_banner_details") }} <i class="customize-fn_show"></i>
				</a>
			</div>

			<div id="banner_details" class="well mb50">
				@include('webshoppack::shopBanner')
			</div>
		{{-- Shop Banner ends here --}}

		{{-- Shop Address starts here --}}
			<div class="showhide-address title-two">
				<a href="javascript:void(0);" class="fn_clsDropAddress btn-link" onclick="showHidepanels(this, 'address_details')">
					{{ trans("webshoppack::shopDetails.shop_address_details") }} <i class="customize-fn_show"></i>
				 </a>
			</div>

			<div id="address_details" class="clearfix well mb50">
				@include('webshoppack::shopAddress')
			</div>
		{{-- Shop Address ends here --}}

	</section>

	<div id="dialog-delete-confirm" class="confirm-dialog-delete" title="" style="display:none;">
          <p><span class="ui-icon ui-icon-alert"></span><small>{{  trans('webshoppack::shopDetails.shopdetails_banner_image_confirm') }}</small></p>
    </div>
@stop

@section('script_content')
	<script src="{{ URL::asset('packages/agriya/webshoppack/js/jquery.form.js') }}"></script>
	<script src="{{ URL::asset('packages/agriya/webshoppack/js/bootstrap/bootstrap.file-input.js') }}"></script>
	<script type="text/javascript">
	    function showHidepanels(obj, div_id) {
	    	var link_class = obj.className;
	    	$('#'+div_id).slideToggle(500);
	    	// toggle open/close symbol
	        var span_elm = $('.'+link_class+' i');
	        if(span_elm.hasClass('customize-fn_show')) {
	        	span_elm.removeClass('customize-fn_show');
				span_elm.addClass('customize-fn_hide');
	        }
			else {
				span_elm.removeClass('customize-fn_hide');
	        	span_elm.addClass('customize-fn_show');
	        }
	        return false;
	    }

	    var mes_required = "{{trans('webshoppack::common.required')}}";
		$("#shopanalytics_frm").validate({
			rules: {
				shop_analytics_code: {
					required: true
				}
			},
			messages: {
				shop_analytics_code:{
					required: mes_required
				}
			}
		});

		jQuery.validator.addMethod("slug", function(value, element) {
			return this.optional(element) || /^([a-z0-9_-])+$/i.test(value);
		}, "Alpha-numeric characters, Dashes, and Underscores only please");

		var doSubmit = function(){
			var frmname = arguments[0];
			var divname = arguments[1];

			var form_validated = true;
			if(frmname != "shopaddress_frm")
			{
				var validator = $("#"+frmname).validate({  });
		 		if(!$("#"+frmname).valid())
				{
					form_validated = false;
				}
			}

		 	if(form_validated)
			{
				displayLoadingImage(true);
				var options = {
			    	target:     '#'+divname,
			    	url:        $("#"+frmname).attr('action'),
			    	success: function(responseData)
					{
						if(frmname == "shopbanner_frm")
						{
							$('#success_div').hide();
						}
						hideLoadingImage(true);
					}
				};
				// pass options to ajaxSubmit
				$('#'+frmname).ajaxSubmit(options);
			}
			else
			{
				validator.focusInvalid();
			}
		};

		var common_no_label = "{{ trans('webshoppack::common.cancel') }}" ;
		var common_yes_label = "{{ trans('webshoppack::common.yes') }}" ;
		var package_name = "{{ Config::get('webshoppack::package_name') }}" ;

		function removeShopImage(resource_id, imagename, imageext, imagefolder) {
			$("#dialog-delete-confirm").dialog({
				title: package_name,
				modal: true,
				buttons: [{
						text: common_yes_label,
						click: function()
						{
							displayLoadingImage();
							$.getJSON("{{ Url::action('Agriya\Webshoppack\ShopController@getDeleteShopImage') }}",
							{resource_id: resource_id, imagename: imagename, imageext: imageext, imagefolder: imagefolder},
								function(data)
								{
									hideLoadingImage();
									if(data.result == 'success')
									{
										$('#itemResourceRow_'+resource_id).remove();
										$('#success_div').show();
										$('#success_msg_div').hide();
										$('#success_div').html("{{trans('webshoppack::shopDetails.shopdetails_banner_deleted_success')}}");
									}
									else
									{
										$('#success_div').hide();
										$('#success_msg_div').hide();
										showErrorDialog({status: 'error', error_message: '{{ trans('webshoppack::common.invalid_action') }}'});
									}
							});
							$(this).dialog("close");
						}
					},
					{
						text: common_no_label,
						click: function()
						{
							 $(this).dialog("close");
						}
					}
				]});
		}
	</script>
	<script type="text/javascript">
        var mes_required = "{{ trans('webshoppack::common.required') }}";
        var valid_email = "{{ trans('webshoppack::shopDetails.not_valid_email') }}";
        $("#shoppaypal_frm").validate({
            rules: {
                paypal_id: {
                    required: true,
                    email: true
                },
            },
            messages: {
                paypal_id: {
                    required : mes_required,
                    email: valid_email
                },
            }
        });
    </script>
    <script type="text/javascript">
		var mes_required = "{{trans('webshoppack::common.required')}}";
		$(document).ready(function() {
			var desc_max = "{{ Config::get('webshoppack::fieldlength_shop_description_max') }}";
			var contactinfo_max = "{{ Config::get('webshoppack::fieldlength_shop_contactinfo_max') }}";
			$('#shop_desc').keyup(function(e) {
				var text_length = $('#shop_desc').val().length;
				var text_remaining = desc_max - text_length;
				if(text_remaining >= 0)
				{
					$('#shop_desc_count').html(text_remaining + ' characters left');
				}
				else
				{
					 $('#shop_desc').val($('#shop_desc').val().substring(0, desc_max));
				}
			});

			$('#shop_contactinfo').keyup(function(e) {
				var text_length = $('#shop_contactinfo').val().length;
				var text_remaining = contactinfo_max - text_length;
				if(text_remaining >= 0)
				{
					$('#shop_contactinfo_count').html(text_remaining + ' characters left');
				}
				else
				{
					 $('#shop_contactinfo').val($('#shop_contactinfo').val().substring(0, contactinfo_max));
				}
			});

			$('#shop_name').focusout(function() {
				if ($('#url_slug').val() == '') {
					var tmp_str = $('#shop_name').val().replace(/\s/g,'-'); // to replace spaces with hypens
					tmp_str = tmp_str.replace(/[\-]+/g,'-');	// to remove extra hypens
					tmp_str = tmp_str.replace(/[^a-zA-Z0-9\-]/g,'').toLowerCase(); // to convert to lower case and only allow alpabets and number and hypehn
					tmp_str = alltrimhyphen(tmp_str);
					$('#url_slug').val(tmp_str);
				}
			});
			$('#url_slug').focusout(function() {
				if ($('#url_slug').val() != '') {
					var tmp_str = $('#url_slug').val().replace(/\s/g,'-'); // to replace spaces with hypens
					tmp_str = tmp_str.replace(/[\-]+/g,'-');	// to remove extra hypens
					tmp_str = tmp_str.replace(/[^a-zA-Z0-9\-]/g,'').toLowerCase(); // to convert to lower case and only allow alpabets and number and hypehn
					tmp_str = alltrimhyphen(tmp_str);
					$('#url_slug').val(tmp_str);
				}
			});
			function alltrimhyphen(str) {
				return str.replace(/^\-+|\-+$/g, '');
			}
		});

		$("#shoppolicy_frm").validate({
			rules: {
				shop_name: {
					required: true,
					minlength: "{{ Config::get('webshoppack::shopname_min_length') }}",
					maxlength: "{{ Config::get('webshoppack::shopname_max_length') }}"
				},
				url_slug: {
					required: true
				},
				shop_slogan: {
					minlength: "{{ Config::get('webshoppack::shopslogan_min_length') }}",
					maxlength: "{{ Config::get('webshoppack::shopslogan_max_length') }}",
				},
				shop_desc: {
					minlength: "{{ Config::get('webshoppack::fieldlength_shop_description_min') }}",
					maxlength: "{{ Config::get('webshoppack::fieldlength_shop_description_max') }}",
				},
				shop_status: {
					required: true
				},
				shop_contactinfo: {
					minlength: "{{ Config::get('webshoppack::fieldlength_shop_contactinfo_min') }}",
					maxlength: "{{ Config::get('webshoppack::fieldlength_shop_contactinfo_max') }}",
				}
			},
			messages: {
				shop_name: {
					required : mes_required,
					minlength: jQuery.format("{{ trans('webshoppack::shopDetails.shopname_min_length') }}"),
					maxlength: jQuery.format("{{ trans('webshoppack::shopDetails.shopname_max_length') }}")
				},
				url_slug: {
					required : mes_required,
				},
				shop_slogan: {
					minlength: jQuery.format("{{ trans('webshoppack::shopDetails.shopslogan_min_length') }}"),
					maxlength: jQuery.format("{{ trans('webshoppack::shopDetails.shopslogan_max_length') }}"),
				},
				shop_status: {
					required : mes_required,
				}
			}
		});
	</script>
	<script>
		$(document).ready(function(){
			$('input[type=file]').bootstrapFileInput();
		});
	</script>
@stop