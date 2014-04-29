<aside class="col-lg-3">
    @if($d_arr['error_msg'] == '')
        <?php
            $user_details = Agriya\Webshoppack\CUtil::getUserDetails($p_details['product_user_id']);
        ?>
        <div class="mb20">
        {{-- Price details start --}}
            @if($p_details['is_free_product'] == 'No')
            <?php
                $product_price = Agriya\Webshoppack\CUtil::getCurrencyBasedAmount($p_details['product_price'], $p_details['product_price_usd'], $p_details['product_price_currency']);
                $product_price_arr = Agriya\Webshoppack\CUtil::getCurrencyBasedAmount($p_details['product_price'], $p_details['product_price_usd'], $p_details['product_price_currency'], true);
                $discount_price = 0;
                if($p_details['product_discount_todate'] != '0000-00-00' || $p_details['product_discount_fromdate'] != '0000-00-00')
                {
                    $discount_from_date = strtotime($p_details['product_discount_fromdate']);
                    $discount_end_date = strtotime($p_details['product_discount_todate']);
                    $curr_date = strtotime(date('Y-m-d'));
                    if($discount_end_date >= $curr_date && $discount_from_date <= $curr_date)
                    {
                        $discount_price = Agriya\Webshoppack\CUtil::getCurrencyBasedAmount($p_details['product_discount_price'], $p_details['product_discount_price_usd'], $p_details['product_price_currency']);
                        $discount_price_arr = Agriya\Webshoppack\CUtil::getCurrencyBasedAmount($p_details['product_discount_price'], $p_details['product_discount_price_usd'], $p_details['product_price_currency'], true);
                    }
                }
            ?>
            @endif
            <?php $enable_serivices = true; ?>
            @include('webshoppack::admin.displayProductPrice')

        {{-- Price details end --}}



        </div>

        {{-- Item details start --}}
		<div class="aside-bar">
            <div class="title-block">
                <h3>{{ trans('webshoppack::viewProduct.item_details') }}:</h3>
            </div>
            <div class="dl-horizontal">
                {{-- Shop details start --}}
                @if(count($d_arr['shop_details']) > 0)
                    <?php
                        $shop_url = '#';//$service_obj->getProductShopURL($d_arr['shop_details']['id'], $d_arr['shop_details']);
                    ?>
                    <dl>
                        <dt>{{ trans('webshoppack::viewProduct.shop_details') }}</dt>
                        <dd><a href="{{ $shop_url }}" title="{{{ $d_arr['shop_details']['shop_name'] }}}"class="light-link text-ellipsyfy">{{{ $d_arr['shop_details']['shop_name'] }}}</a>{{-- <i class="fa fa-heart"></i> --}}</dd>
                    </dl>
                @endif
                {{-- Shop details end --}}
                <dl>
                    <dt>{{ trans('webshoppack::viewProduct.listed_on') }}</dt>
                    <dd>@if($p_details['date_activated'] == '0000-00-00 00:00:00') {{ trans('webshoppack::viewProduct.not_available') }}  @else {{ date('M d, Y', strtotime($p_details['date_activated']))  }} @endif</dd>
                </dl>
                <dl>
                    <dt>{{ trans('webshoppack::viewProduct.listing_id') }} </dt>
                    <dd>#{{ $p_details['product_code'] }}</dd>
                </dl>
                <dl>
                    <dt>{{ Lang::choice('webshoppack::viewProduct.view_choice', $p_details['total_views']) }}</dt>
                    <dd>{{ $p_details['total_views'] }} </dd>
                </dl>
            </div>
        </div>
        {{-- Item details end --}}

        @if(count($d_arr['shop_item_details']) > 0)
            <?php
                $total_shop_items = $service_obj->getTotalProduct($p_details['product_user_id']);
            ?>
            <div class="aside-bar shop-itemdet">
                <div class="title-block">
                    <h3>{{ trans('webshoppack::viewProduct.more_from_this_shop') }}</h3>
                </div>
                <ul class="list-inline mt10 clearfix">
                    @foreach($d_arr['shop_item_details'] AS $prd)
                        <?php
                            $p_img_arr = $service_obj->populateProductDefaultThumbImages($prd->id);
                            $p_thumb_img = $service_obj->getProductDefaultThumbImage($prd->id, 'thumb', $p_img_arr);
                            $view_url = $service_obj->getProductViewURL($prd->id, $prd);
                        ?>
                        <li>
                            <a href="{{ $view_url }}" class="img81x64"><img src="{{ $p_thumb_img['image_url'] }}" @if(isset($p_thumb_img["thumbnail_width"])) width='{{ $p_thumb_img["thumbnail_width"] }}' height='{{ $p_thumb_img["thumbnail_height"] }}' @endif title="{{{ nl2br($prd->product_name)  }}}" alt="{{{ nl2br($prd->product_name)  }}}" /></a>
                    </li>
                    @endforeach
                    <li><a href='{{ $shop_url }}'><strong>{{ $total_shop_items}}<span class="text-muted">{{Lang::choice('webshoppack::viewProduct.product_choice', $total_shop_items) }}</span></strong></a></li>
                </ul>
            </div>
        @endif


        {{-- Price details start
        	<?php $enable_serivices = false; ?>
        	@include('webshoppack::admin.displayProductPrice')
        Price details end --}}
    @endif
</aside>