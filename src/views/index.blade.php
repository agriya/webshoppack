@extends(\Config::get('webshoppack::base_view'))
@section('content')
	<h1 class="title-one">Items <span class="text-muted">({{$product_total_count}})</span></h1>
	<div class="row">
	    <div class="col-lg-9">
	        @if(Session::has('error_message') && Session::get('error_message') != '')
	            <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
	            <?php Session::forget('error_message'); ?>
	        @endif

	        @if(Session::has('success_message') && Session::get('success_message') != '')
	            <div class="alert alert-success">{{ Session::get('success_message') }}</div>
	            <?php Session::forget('success_message'); ?>
	        @endif

			<div class="well">
	            @if(count($product_details) > 0)
	            	<?php $seller_details = array(); ?>
	                <ul class="list-unstyled clearfix no-mar" id="js-product-view">
	                    @foreach($product_details as $productKey => $product)
	                        <li class="pro-lists store-lists clearfix">
	                        	<div class="lists-inner">
									<?php
										$seller_id = $product['product_user_id'];
										$seller_details = Agriya\Webshoppack\CUtil::getUserDetails($seller_id);
										$seller_name = "";
										if(isset($seller_details['display_name']) && $seller_details['display_name'] != "") {
											$seller_name = $seller_details['display_name'];
										}

	                                    $p_img_arr = $list_prod_serviceobj->populateProductDefaultThumbImages($product['id']);
	                                    $p_thumb_img = $list_prod_serviceobj->getProductDefaultThumbImage($product['id'], 'thumb', $p_img_arr);
	                                    $price = $list_prod_serviceobj->formatProductPrice($product);
	                                    $view_url = $list_prod_serviceobj->getProductViewURL($product['id'], $product_details);
	                                ?>
	                                <figure>
                                    <a href="{{ $view_url }}"><img id="item_thumb_image_id" src="{{$p_thumb_img['image_url']}}" title="{{{ $product['product_name']  }}}" alt="{{{ $product['product_name']  }}}" /></a>
                                </figure>
	                                <div class="pro-listsdetail row">
	                                    <div class="col-lg-8 plft0">
	                                        <h2 class="title-six"><a href="{{$view_url}}" title="{{{ $product['product_name']  }}}">{{{ $product['product_name'] }}}</a></h2>
	                                        <p class="pro-des">{{ $seller_name }}</p>
	                                        <p class="pro-des">{{{ $product['product_highlight_text'] }}}</p>
	                                    </div>
	                                    <div class="col-lg-4 buy-block clearfix">
                                            <p class="price-value pull-left">
                                                @if($product['is_free_product'] == 'Yes')
                                                    <sub>{{ Lang::get('webshoppack::common.free') }}</sub>
                                                @else
                                                	@if($price['disp_price'] && $price['disp_discount'])
                                                        {{ Agriya\Webshoppack\CUtil::getCurrencyBasedAmount($product['product_discount_price'], $product['product_discount_price_usd'], $product['product_price_currency']) }}
                                                    @elseif($price['disp_price'])
                                                        @if($product['product_price'] > 0)
                                                            {{ Agriya\Webshoppack\CUtil::getCurrencyBasedAmount($product['product_price'], $product['product_price_usd'], $product['product_price_currency']) }}
                                                        @else
                                                           <sub>{{ Lang::get('webshoppack::common.free') }}</sub>
                                                        @endif
                                                    @endif
                                                @endif
                                            </p>
	                                    </div>
	                                </div>
								</div>
	                        </li>
	                    @endforeach
	                </ul>
	            @else
	                <p class="alert alert-info">{{ Lang::get('webshoppack::product.product_not_found') }}</p>
	            @endif
			</div>
	        @if(count($product_details) > 0)
	            <div class="text-right">{{ $product_details->appends(array('cat_search' => Input::get('cat_search'), 'author_search' => Input::get('author_search'), 'tag_search' => Input::get('tag_search'), 'shop_search' => Input::get('shop_search'), 'price_range_start' => Input::get('price_range_start'), 'price_range_end' => Input::get('price_range_end'), 'orderby_field' => Input::get('orderby_field'), 'search_products' => Input::get('search_products')))->links() }}</div>
	        @endif
	    </div>
	    @include('webshoppack::productLeftMenu')
	</div>
@stop
@section('script_content')
	<script language="javascript" type="text/javascript">
		function clearForm(oForm)
		{
			var elements = oForm.elements;
			oForm.reset();
			$('#cat_search').val();
			$('#cat_search').attr("checked", 'false');
			$('#cat_search').click();

			for(i=0; i<elements.length; i++)
			{
		 		field_type = elements[i].type.toLowerCase();

			   	switch(field_type)
			 	{
					case "text":
					case "textarea":
					  	elements[i].value = "";
					break;
					case "checkbox":
						if (elements[i].checked)
						{
							elements[i].checked = false;
						}
					break;
					case "select-one":
					case "select-multi":
					case "select-multiple":
						elements[i].selectedIndex = -1;
					break;
				}
		    }
			document.productSearchfrm.submit();
		}
	</script>
@stop