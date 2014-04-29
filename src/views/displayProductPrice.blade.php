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

    @if(isset($enable_serivices) && $enable_serivices)
        <div class="clearfix">
            <div class="rec-services">
                @if(isset($product_service_details) && count($product_service_details) > 0)
                    @foreach($product_service_details as $product_service)
                    <ul class="list-unstyled clearfix">
                        <li>{{Form::checkbox('productservices[]',$product_service['id'], false, array('class'=> 'js-service-checkbox', 'data-price' =>$product_service['price'] ))}}</li> 			                        <li class="rec-sername" title="{{{$product_service['service_name']}}}">{{{$product_service['service_name']}}}</li>
                        <li class="min-width52">
                        <?php $price_det = CUtil::getCurrencyBasedAmount($product_service['price'], $product_service['price'], 'USD');?>
                            <p class="price-value">{{$price_det}}</p>
                        </li>
                        <li class="text-muted"><i title="{{{$product_service['service_name']}}}" class="fa fa-question-circle"></i></li>
                    </ul>
                    @endforeach

                    {{Form::hidden('org_price', $subtotal_price_arr['amt'], array('id' => 'orgamount'))}}
                    <ul class="list-unstyled clearfix">
                        <li class="min-width140">Subtotal Price:</li>
                        <li><p class="price-value"><sup>{{$subtotal_price_arr['currency_symbol_font']}}</sup><sub id="subtotal_price">{{$subtotal_price_arr['amt']}}</sub></p></li>
                    </ul>
                @endif
            </div>
        </div>
    @endif

	<div id="showAddCartBtn" class="js_showAddCartBtn">
		@if(Sentry::check())
			<button name="buy_now" id="buy_now" value="buy_now" type="button" class="btn btn-success btn-lg btn-block custom-btn2">{{ trans('webshoppack::viewProduct.buy_now') }}</button>
		@else
			<a href="{{ url('users/login?form_type=selLogin') }}" class="fn_signuppop showAddCartBtn btn btn-success btn-lg btn-block custom-btn2" id="buy_now" action="{{ url('users/signup-pop/selLogin') }}">{{ trans('webshoppack::viewProduct.buy_now') }}</a>
		@endif
		<div id="addCartButton" class="clearfix" style="display:none;">
			<button type="button" name="add_to_cart" value="add_to_cart" class="btn btn-primary btn-lg custom-btn4 col-lg-12"><i class="fa fa-shopping-cart"></i> {{ trans("webshoppack::viewProduct.add_to_cart") }}</button>
		</div>
	</div>
    @if($p_details['allow_to_offer'] == 'Yes')
		<hr />
		@if(isLoggedin())
			<a href="@if($preview_mode){{ $no_url }} @else {{ Url::to('user/message/add/'.$user_details['user_code'].'/'.$p_details['product_code'].'/offer') }} @endif" class="@if($preview_mode) disabled @else fn_signuppop @endif btn btn-info btn-lg btn-block custom-btn1">{{ trans('webshoppack::viewProduct.make_an_offer') }}</a>
		@else
			<a href="{{ url('users/login?form_type=selLogin') }}" class="fn_signuppop btn btn-info btn-lg btn-block custom-btn1">{{ trans('webshoppack::viewProduct.make_an_offer') }}</a>
		@endif
	@endif
</div>