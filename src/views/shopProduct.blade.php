@if(count($product_details) > 0)
	<ul class="list-unstyled">
	@foreach($product_details as $productKey => $product)
		<li class="pro-lists store-lists clearfix">
			<?php
				$p_img_arr = $service_obj->populateProductDefaultThumbImages($product['id']);
				$p_thumb_img = $service_obj->getProductDefaultThumbImage($product['id'], 'thumb', $p_img_arr);
				$price = $service_obj->formatProductPrice($product);
				$view_url = $service_obj->getProductViewURL($product['id'], $product_details);
			?>
			<figure>
				<a href="{{ $view_url }}"><img id="item_thumb_image_id" src="{{$p_thumb_img['image_url']}}" @if(isset($p_thumb_img["thumbnail_width"])) width='{{$p_thumb_img["thumbnail_width"]}}' height='{{$p_thumb_img["thumbnail_height"]}}' @endif title="{{{ $product['product_name']  }}}" alt="{{{ $product['product_name']  }}}" /></a>
			</figure>

			<div class="pro-listsdetail row">
				<div class="col-lg-8 plft0">
					<h2 class="title-six"><a href="{{ $view_url }}" title="{{{ $product['product_name']  }}}">{{{ $product['product_name'] }}}</a></h2>
					{{{ $product['product_highlight_text'] }}}
				</div>
				<div class="clearfix col-lg-4 buy-block">
					<p class="pull-left price-value2">
						@if($product['is_free_product'] == 'Yes')
							<strong class="title-three"><span class="text-info">{{ trans('webshoppack::common.free') }}</span></strong>
						@else
							@if($price['disp_price'] && $price['disp_discount'])
								{{ Agriya\Webshoppack\CUtil::getCurrencyBasedAmount($product['product_discount_price'], $product['product_discount_price_usd'], $product['product_price_currency']) }}
							@elseif($price['disp_price'])
								@if($product['product_price'] > 0)
									{{ Agriya\Webshoppack\CUtil::getCurrencyBasedAmount($product['product_price'], $product['product_price_usd'], $product['product_price_currency']) }}
								@else
									<strong class="title-three"><span class="text-info">{{ trans('webshoppack::common.free') }}</span></strong>
								@endif
							@endif
						@endif
					</p>
					<div class="ml15">
						@if(Config::get("webshoppack::is_logged_in"))
							{{ Form::open(array('url' => '', 'method' => 'post', 'class' => 'form-horizontal',  'id' => 'checkOutfrm', 'name' => 'checkOutfrm')) }}
								{{ Form::hidden('pid', $product['id'], array("id" => "pid"))}}
								{{ Form::hidden('type', 'product', array("id" => "type"))}}
								<button name="buy_now" id="buy_now" value="buy_now" type="button" class="btn btn-success custom-btn2 btn-sm pull-right">{{ trans('webshoppack::shop.buy_now_label') }}</button>
							{{ Form::close() }}
						@else
							<a href="#">
								<button name="buy_now" id="buy_now" value="buy_now" type="button" class="btn btn-success custom-btn2 btn-sm pull-right">{{ trans('webshoppack::shop.buy_now_label') }}</button>
							</a>
						@endif
					</div>
				</div>
			</div>
		</li>
	@endforeach
</ul>
@else
    <p class="alert alert-info">{{ trans("webshoppack::shop.product_not_found") }}</p>
@endif