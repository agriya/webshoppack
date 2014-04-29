<div class="aside-box1">
	<p class="@if($p_details['is_free_product'] == 'Yes') text-right @else mt10 pull-right @endif"><small>{{ trans('webshoppack::viewProduct.delivery') }}:@if($p_details['delivery_days'] > 0) <strong>{{ $p_details['delivery_days'].' '.Lang::choice('webshoppack::viewProduct.day_choice', $p_details['delivery_days']) }}</strong> @else <strong>{{ trans('webshoppack::viewProduct.instant_delivery') }}</strong> @endif</small></p>
	<div class="mb10">
        @if($p_details['is_free_product'] == 'Yes')
            <div class="btn btn-info btn-lg btn-block">{{ trans('webshoppack::viewProduct.free') }}</div>
        @else
            @if($discount_price)
            	<?php $subtotal_price_arr = $discount_price_arr;?>
                <p class='price-value strike-out'>{{ $product_price }}</p>
                <p class="price-value" id="finalprice">{{ $discount_price }}</p>
            @else
            	<?php $subtotal_price_arr = $product_price_arr;?>
            	<p class="price-value" id="finalprice">{{ $product_price }}</p>
            @endif
        @endif
    </div>
</div>