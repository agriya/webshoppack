@extends(\Config::get('webshoppack::package_layout'))
@section('content')
<h1 class="title-one">{{ trans('webshoppack::shop.listShops.shoplist_title') }}</h1>
<div class="row">
    <section class="col-lg-9">
        {{-- Listing block start --}}
		<div class="well">
            {{ Form::open(array('id'=>'shopsListfrm', 'method'=>'get','class' => 'form-horizontal form-request' )) }}
                @if(count($shops_list))
                    <ul class="list-unstyled shop-list">
                        <?php
                            //$service_obj = new ProductService;
                         ?>
                        @foreach($shops_list as $shop)
                            <?php
                                $shop_user_details = array("first_name" => $shop->first_name, "last_name" => $shop->last_name, "user_code" => $shop->user_code);
                                $shop_details['url_slug'] = $shop->url_slug;
                                $shop_items = $service_obj->fetchShopItems($shop->user_id, 0, 3);
                                $shop_url = $service_obj->getProductShopURL($shop->id, $shop_details);
                                $user_details = Agriya\Webshoppack\CUtil::getUserDetails($shop->user_id);//, 'all', $shop_user_details
                            ?>
                            <li class="pro-lists store-lists clearfix">
                                <div class="pull-left">
                                    <div class="pro-listsdetail">
                                        <p><a href="{{ URL::to(Config::get('webshoppack::shop_uri').'/'.$shop->url_slug) }}" title="{{{ $shop->shop_name }}}" class="light-link font-s18"><strong>{{{ $shop->shop_name }}}</strong></a></p>
                                        @if($shop->shop_city != '' && $shop->shop_state != '' && $shop->shop_country != '')
                                            <p>{{{ $shop->shop_city }}}, {{{ $shop->shop_state }}}, {{{ $country_arr[$shop->shop_country] }}}</p>
                                        @elseif($shop->shop_state != '' && $shop->shop_country != '')
                                            <p>{{{ $shop->shop_state }}}, {{{ $shop->shop_country }}}</p>
                                        @elseif($shop->shop_country != '')
                                            <p>{{{ $country_arr[$shop->shop_country] }}}</p>
                                        @endif
                                        <p>{{ trans('webshoppack::shop.listShops.shop_owner') }}:&nbsp; <a href="{{$user_details['profile_url']}}" title="{{ $user_details['display_name'] }}" class="btn-link">{{ $user_details['display_name'] }}</a></p>
                                    </div>
                                </div>
                                <div class="pull-right ml15">
                                    <ul class="list-unstyled list-inline shop-itemdet listshop-det">
                                            @foreach($shop_items AS $prd)
                                                <?php
                                                    $p_img_arr = $service_obj->populateProductDefaultThumbImages($prd->id);
                                                    $p_thumb_img = $service_obj->getProductDefaultThumbImage($prd->id, 'thumb', $p_img_arr);
                                                    $view_url = $service_obj->getProductViewURL($prd->id, $prd);
                                                ?>
                                                <li>
                                                    <a href="{{ $view_url }}" class="img81x64"><img src="{{ $p_thumb_img['image_url'] }}" @if(isset($p_thumb_img["thumbnail_width"])) width='{{ $p_thumb_img["thumbnail_width"] }}' height='{{ $p_thumb_img["thumbnail_height"] }}' @endif title="{{{ $prd->product_name  }}}" alt="{{{ $prd->product_name  }}}" /></a>
                                                </li>
                                            @endforeach
                                            <li class="shop-border"><a href="{{ URL::to(Config::get('webshoppack::shop_uri').'/'.$shop->url_slug) }}" title="{{ $shop_url }}"><strong>{{ $shop->total_products }} <span class="text-muted">{{Lang::choice('webshoppack::viewProduct.product_choice', $shop->total_products) }}</span></strong></a></li>
                                    </ul>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-info">{{ trans('webshoppack::shop.listShops.no_shops_found') }}</div>
                @endif
                <div class="text-center">
                    {{ $shops_list->appends(array('owner_name' => Input::get('owner_name'), 'shop_name' => Input::get('shop_name'), 'q' => Input::get('q')))->links() }}
                </div>
             {{ Form::close() }}
		</div>
         {{-- Listing block ends --}}
	</section>
    <aside class="col-lg-3">
        {{ Form::open(array('url' => Request::url(), 'class' => 'form-horizontal aside-bar',  'id' => 'searchShopsfrm', 'name' => 'searchShopsfrm', 'method' => 'get')) }}
        	<div class="title-block"><h3>Filter your search</h3></div>
            <div class="form-group">
                {{ Form::label('owner_name','',array('class' => 'control-label')) }}
                {{ Form::text('owner_name', Input::get('owner_name'),array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('shop_name','',array('class' => 'control-label')) }}
                {{ Form::text('shop_name', Input::get('shop_name'),array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-sm">{{ trans('webshoppack::common.search')}}</button>
                <a href="{{ Request::url() }}" class="btn btn-default btn-sm">{{ trans('webshoppack::common.reset') }}</a>
            </div>
         {{ Form::close() }}
    </aside>
</div>
@stop