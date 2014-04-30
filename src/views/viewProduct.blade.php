@extends(\Config::get('webshoppack::package_layout'))
@section('content')
	<?php
		$logged_user_id = (Sentry::getUser())? Sentry::getUser()->user_id : 0;
		$shop_url = '';
		$url_str = '';
		$no_url = 'javascript:void(0)';
	?>
	@if($d_arr['error_msg'] != '')
		<div class="alert alert-danger">{{ trans($d_arr['error_msg']) }}</div>
    @else
		<h1 class="title-one">{{{ $product_title }}}</h1>
    @if($d_arr['alert_msg'] != '')
		<div class="alert alert-info">{{ trans($d_arr['alert_msg']) }}</div>
    @endif

    <ul class="nav nav-tabs custom-tabmenu">
        <li @if(Route::currentRouteAction()== 'ViewProductController@getIndex') class="active" @endif>
            <a href="{{ $d_arr['view_url'] }}" itemprop="url"><strong>Item Details</strong></a>
        </li>
    </ul>

    <div class="row viewpro-store">
        <section class="col-lg-9">
        	<div class="well">

                {{-- Slider block start --}}
                    <div class="connected-carousels mb50">
                        <div class="stage">
                            <div class="carousel carousel-stage">
                                <ul>
                                    @if(count($d_arr['slider_default_img']) > 0)
                                        @if($d_arr['slider_default_img']['image_exits'])
                                            <li><a class="fn_fancyboxview" rel="screenshots_group" href="{{ $d_arr['slider_default_img']['orig_img_path'] }}" title="{{{ $d_arr['slider_default_img']['title'] }}}" ><img src="{{ $d_arr['slider_default_img']['large_img_path'] }}" {{ $d_arr['slider_default_img']['large_img_attr'] }} title="{{{ $d_arr['slider_default_img']['title'] }}}" /></a></li>
                                        @else
                                            <li><img src="{{ $d_arr['slider_default_img']['large_img_path'] }}" {{ $d_arr['slider_default_img']['large_img_attr'] }} title="{{{ $d_arr['slider_default_img']['title'] }}}" /></li>
                                        @endif
                                    @endif
                                    @if(count($d_arr['slider_preview_img']) > 0)
                                        @foreach($d_arr['slider_preview_img'] AS $img)
                                            <li><a class="fn_fancyboxview" rel="screenshots_group" href="{{ $img['orig_img_path'] }}" title="{{{ $img['title'] }}}" ><img src="{{ $img['large_img_path'] }}" {{ $img['large_img_attr'] }} title="{{{ $img['title'] }}}" /></a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        {{-- Thumb Slider start --}}
	                    @if($p_details['product_preview_type'] == 'image')
	                        <div class="navigation viewpage-slide">
	                        	<a href="#" class="prev prev-navigation pull-left"><i class="fa fa-chevron-left"></i></a>
			                    <a href="#" class="next next-navigation pull-right"><i class="fa fa-chevron-right"></i></a>
	                            <div class="carousel carousel-navigation">
	                                <ul class="list-inline">
	                                    @if(count($d_arr['slider_default_img']) > 0)
	                                        <li><img src="{{ $d_arr['slider_default_img']['thumb_img_path'] }}" {{ $d_arr['slider_default_img']['thumb_img_attr'] }} title="{{{ $d_arr['slider_default_img']['title'] }}}" /></li>
	                                    @endif
	                                    @if(count($d_arr['slider_preview_img']) > 0)
	                                        @foreach($d_arr['slider_preview_img'] AS $img)
	                                            <li><img src="{{ $img['thumb_img_path'] }}" {{ $img['thumb_img_attr'] }} title="{{{ $img['title'] }}}" /></li>
	                                        @endforeach
	                                    @endif
	                                </ul>
	                            </div>
	                        </div>
	                    @endif
	                    {{-- Thumb Slider end --}}
                    </div>
                {{-- Slider block end --}}
            @endif

            {{-- Share URL block start --}}

                    <div class="social-sharelinks clearfix">
                        @if($p_details['demo_url'] != '')
                            {{--<div class="pull-right mt7"><i class="fa fa-search"></i> <a href='{{ $p_details['demo_url'] }}' class="light-link" target="_blank"><strong>{{ trans('webshoppack::viewProduct.view_demo') }}</strong></a></div>--}}
							<div class="pull-right mt7"><i class="fa fa-search"></i> <a href='{{ Url::to('item/'.$p_details['product_code'].'-'.$p_details['url_slug'].'/demo') }}' class="light-link" target="_blank"><strong>{{ trans('webshoppack::viewProduct.view_demo') }}</strong></a></div>
                        @endif

					</div>

            {{-- Share URL block end --}}
            <hr />

            {{-- Product bottom block start  --}}
            @if($d_arr['error_msg'] == '')
            	<!--<div class="clearfix">
                    @if($logged_user_id == $p_details['product_user_id'])
                        <a href='@if($preview_mode){{ $no_url }} @else {{ URL::to('myaccount/my-shop-queries?srchquery_submit=submit&srch_product_id='.$p_details['id']) }} @endif' class="light-link pull-right"><i class="fa fa-reply"></i> {{ trans('webshoppack::viewProduct.manage_replies') }}</a>
                    @endif

                    <strong>{{ trans('webshoppack::viewProduct.need_more_information') }}</strong>
                    @if(Sentry::check())
                        <a href='@if($preview_mode){{ $no_url }} @else{{ Request::url().'?query=add' }}@endif' class="light-link">{{ trans('webshoppack::viewProduct.ask_the_seller_question') }}</a>
                    @else
                        <a href="{{ url('users/login?form_type=selLogin') }}" class="fn_signuppop light-link">{{ trans('webshoppack::viewProduct.ask_the_seller_question') }}</a>
                    @endif
                </div>-->

                <div class="clearfix mt30 mb20">
                    <h3 class="title-two">{{ trans('webshoppack::viewProduct.description') }}</h3>
                   {{nl2br($p_details['product_description'])}}
                </div>

            @endif
            {{-- Product bottom block end  --}}

			</div>
        </section>

        {{-- Right block start --}}
            @include(\Config::get('webshoppack::view_productright'))
        {{-- Right block end --}}
    </div>

@stop
@section('script_content')

@if($d_arr['error_msg'] == '')

		<script src="{{ URL::asset('packages/agriya/webshoppack/js/lib/jcarousel-0.3.0/js/jquery.jcarousel.min.js') }}"></script>
		<script src="{{ URL::asset('packages/agriya/webshoppack/js/lib/jcarousel-0.3.0/js/jcarousel.connected-carousels.js') }}"></script>

	<script language="javascript" type="text/javascript">
		$(".fn_signuppop").fancybox({
	        maxWidth    : 800,
	        maxHeight   : 630,
	        fitToView   : false,
	        width       : '70%',
	        height      : '430',
	        autoSize    : false,
	        closeClick  : false,
	        type        : 'iframe',
	        openEffect  : 'none',
	        closeEffect : 'none'
	    });
		@if(!$preview_mode)

		 	$(".fn_ChangeStatus").fancybox({
		        maxWidth    : 772,
		        maxHeight   : 432,
		        fitToView   : false,
		        width       : '70%',
		        height      : '432',
		        autoSize    : true,
		        closeClick  : true,
		        type        : 'iframe',
		        openEffect  : 'none',
		        closeEffect : 'none'
		    });
		@endif

		$(document).ready(function() {
			$(".fn_fancybox").fancybox({
				openEffect	: 'none',
				closeEffect	: 'none'
			});
		});

		$(".fn_fancyboxview").fancybox({
		beforeShow: function() {
				$(".fancybox-wrap").addClass('view-proprevw');
			},
		        maxWidth    : 772,
		        maxHeight   : 432,
		        fitToView   : false,
		        autoSize    : true,
		        closeClick  : true,
		        openEffect  : 'none',
		        closeEffect : 'none'
		    });
	</script>

@endif
	<script language="javascript" type="text/javascript">
		var like_ajax = 0;
		$(document).ready(function() {
			$(".js_showAddCartBtn").hover(function() {
				$(this).children('#addCartButton').slideToggle('fast');
		    });
		});

		$('.js-service-checkbox').click(function() {
			var final_price = $('#orgamount').val();
			final_price = parseFloat(final_price);
			var services_price = 0;
			var service_ids = [];
			$.each($("input[name='productservices[]']:checked"), function() {
				service_ids.push($(this).val());
				services_price = services_price + $(this).data('price');
			});
			service_ids.join(',');
			var subtotal_price = final_price+services_price;
			$('#subtotal_price').html(subtotal_price);
			$('#product_services').val(service_ids);
		});

		$('.fn_clsDescMore').click(function() {
			$(this).parent().hide();
			$(this).parent().next().show();
		});
		$('.fn_clsDescLess').click(function() {
			$(this).parent().hide();
			$(this).parent().prev().show();
		});
	</script>
@stop