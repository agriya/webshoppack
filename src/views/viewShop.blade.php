@extends(Config::get('webshoppack::package_layout'))
@section('content')
	@if(!$shop_status && $viewShopServiceObj->current_user)
		<div class="alert alert-danger">{{ trans("webshoppack::shop.shopstatus_access_owner") }}</div>
	@endif
	@if(!$shop_status && !$viewShopServiceObj->current_user)
		@if(count($shop_details) > 0 && $shop_details['shop_message'] != "")
			<div class="alert alert-danger">{{ $shop_details['shop_message'] }}</div>
		@else
			<div class="alert alert-danger">{{ trans("webshoppack::shop.shopstatus_access") }}</div>
		@endif
	@else
        <h1 class="title-one">{{ trans("webshoppack::shop.shop_products") }}</h1>
		<div class="row">
			<section class="col-lg-9">
				<div class="well">
					@if(count($shop_details) > 0)
						{{-- Shop Banner --}}
						<?php
							$shop_img = Agriya\Webshoppack\ShopService::getShopImage($shop_details->id, 'thumb', $shop_details);
						?>
						@if($shop_img['image_url'] != "")
							<figure class="mb20 text-center view-image">
								<img src="{{$shop_img['image_url']}}" @if(isset($shop_img["thumbnail_width"])) width='{{$shop_img["thumbnail_width"]}}' height='{{$shop_img["thumbnail_height"]}}' @endif title="{{{ $shop_details['shop_name'] }}}" alt="{{{ $shop_details['shop_name'] }}}" />
							</figure>
							<hr>
						@endif
						{{-- Shop Banner ends --}}

						{{-- Shop Description --}}
							@if($shop_details['shop_desc'] != "")
								<p class="mb30">{{ nl2br(Agriya\Webshoppack\CUtil::makeClickableLinks(htmlspecialchars($shop_details['shop_desc']))) }}</p>
							@endif
						{{-- Shop Description ends--}}

						{{-- Product Details starts--}}
							<div id="shop_products">
								@include("webshoppack::shopProduct")
							</div>
						{{-- Product Details ends--}}
					@else
						<p class="alert alert-danger">{{ trans('webshoppack::shop.product_not_found') }}</p>
					@endif
				</div>
                @if((count($shop_details) && count($product_details)) > 0)
                    <div class="text-right">{{ $product_details->appends(array('section_id' => Input::get('section_id')))->links() }}</div>
                @endif
			</section>
			@include("webshoppack::viewShopRightMenu")
		</div>
	@endif
@stop

@section('script_content')
	<script language="javascript" type="text/javascript">
		$("body").delegate('.pagination a', 'click', function() {
			var list_url = $(this).attr('href');
			var queryString = list_url.substr(list_url.indexOf("?") + 1);

			var url_slug = "{{ $url_slug }}";
			var page_url = 'shop/'+ url_slug + '/product-details';
			var product_list_url = "{{ URL::to('" + page_url + "') }}";
			var url = product_list_url + "?" +queryString;

			displayLoadingImage(true);
			$.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
					$('#shop_products').html(data);
					hideLoadingImage(true);
				}
            });
			return false;
		});
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
	</script>
@stop