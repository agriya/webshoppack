@extends(\Config::get('webshoppack::package_layout'))
<title>{{ \Config::get('webshoppack::manage_product_title') }}</title>
@section('content')
<h1 class="title-one">{{ \Lang::get('webshoppack::product.list_product') }}</h1>
<p><a href="{{ URL::to(Config::get('webshoppack::uri').'/add') }}">{{ \Lang::get('webshoppack::product.product_add')  }}</a></p>
@if(Session::has('error_message') && Session::get('error_message') != '')
        <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
        <?php Session::forget('error_message'); ?>
@endif
@if(Session::has('success_message') && Session::get('success_message') != '')
        <div class="alert alert-success">{{ Session::get('success_message') }}</div>
        <?php Session::forget('success_message'); ?>
@endif
@if(count($product_list) <= 0 && !$is_search_done)
	<div class="alert alert-warning">
       {{ \Lang::get('webshoppack::product.list_empty') }}
    </div>
@else
	@if(count($product_list) > 0 || $is_search_done)
	{{ Form::open(array('action' => array('Agriya\Webshoppack\ProductController@productList'), 'id'=>'productFrm', 'method'=>'get','class' => 'form-horizontal' )) }}

		<div class="mb10">
            <div class="showhide-search mb10">
                <a href="javascript:void(0);" class="fn_clsDropSearch btn-default btn btn-sm">
                     @if(Input::get('') == "")
                       {{  \Lang::get('webshoppack::product.show_search_filters') }} <i class="fa fa-caret-down"></i>
                     @else
                    	{{ \Lang::get('webshoppack::product.hide_search_filters') }} <i class="fa fa-caret-up"></i>
                     @endif
                </a>
            </div>
			<div id="search_holder" class="well social-sharelinks" @if(Input::get('') == "") style="display: none;" @endif>
				<h1 class="title-four">{{ \Lang::get('webshoppack::product.search_products') }}</h1>
				<div class="mt30" id="selSrchProducts">
					<fieldset>
						<div class="form-group">
							{{ Form::label('search_product_code', \Lang::get('webshoppack::product.product_code'), array('class' => 'col-lg-2 control-label')) }}
							<div class="col-lg-3">
								{{ Form::text('search_product_code', Input::get("search_product_code"), array('class' => 'form-control valid')) }}
							</div>
						</div>

						<div class="form-group">
							{{ Form::label('search_product_name', \Lang::get('webshoppack::product.search_products'), array('class' => 'col-lg-2 control-label')) }}
							<div class="col-lg-3">
								{{ Form::text('search_product_name', Input::get("search_product_name"), array('class' => 'form-control valid')) }}
							</div>
						</div>

						<div class="form-group">
							{{ Form::label('search_product_category', \Lang::get('webshoppack::product.search_category'), array('class' => 'col-lg-2 control-label')) }}
							<div class="col-lg-3">
								{{ Form::select('search_product_category', $category_list, Input::get("search_product_category"), array('class' => 'form-control valid')) }}
							</div>
						</div>

						<div class="form-group">
							{{ Form::label('search_product_status', \Lang::get('webshoppack::product.status'), array('class' => 'col-lg-2 control-label')) }}
							<div class="col-lg-3">
								{{ Form::select('search_product_status', $status_list, Input::get("search_product_status"), array('class' => 'form-control valid')) }}
							</div>
						</div>

						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button type="submit" name="srchproduct_submit" value="srchproduct_submit" class="btn btn-primary">
								{{ \Lang::get('webshoppack::product.search') }}</button>
								<button type="reset" name="srchproduct_reset" value="srchproduct_reset" class="btn btn-default" onclick="javascript:location.href='{{ Request::url() }}'">
								{{ \Lang::get('webshoppack::product.reset') }}</button>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
        </div>

		<div class="table-view tab-well">
			<table class="table table-striped table-hover table-mobview">
				<thead>
					<tr>
						<th class="col-lg-2">{{ \Lang::get('webshoppack::product.product_code') }}</th>
						<th class="col-lg-2">{{ \Lang::get('webshoppack::product.product_name') }}</th>
						<th class="col-lg-3">{{ \Lang::get('webshoppack::product.product_price') }}</th>
						<th class="col-lg-1">{{ \Lang::get('webshoppack::product.product_sales') }}</th>
						<th class="col-lg-2">{{ \Lang::get('webshoppack::product.status') }}</th>
						<th class="col-lg-2">{{ \Lang::get('webshoppack::product.action') }}</th>
					</tr>
				</thead>
				<tbody>
					@if(count($product_list) > 0)
						@foreach($product_list as $product)
						<?php
								$p_img_arr = $productService->populateProductDefaultThumbImages($product->id);
								$p_thumb_img = $productService->getProductDefaultThumbImage($product->id, 'thumb', $p_img_arr);
								$view_url = $productService->getProductViewURL($product->id, $product);
								//$edit_url = URL::to('products/add?id='.$product->id);
							?>
							<td class="store-lists">
								<figure>
										<a href="{{$view_url}}"><img id="item_thumb_image_id" src="{{$p_thumb_img['image_url']}}" @if(isset($p_thumb_img["thumbnail_width"])) width='{{$p_thumb_img["thumbnail_width"]}}' height='{{$p_thumb_img["thumbnail_height"]}}' @endif title="{{{ nl2br($product['product_name'])  }}}" alt="{{{nl2br($product['product_name'])}}}" /></a>
								</figure>
								<p class="mt10"><span class="text-muted">{{ \Lang::get('webshoppack::product.product_code') }}:</span> {{ $product['product_code'] }}</p>
							</td>
							<td class="store-lists">
								<p  class="text-info">{{ $product['product_name'] }}</p>
							</td>
							<td class="store-lists">
								@if($product['is_free_product'] == 'Yes')
										<span class="text-success">{{ \Lang::get('webshoppack::common.free') }}</span>
									@else
										<p>
											<span class="text-muted">{{ \Lang::get('webshoppack::myProducts.product_price') }}:</span>
											{{ $productService->getBaseAmountToDisplay($product->product_price, $product->product_price_currency) }}
										</p>
										@if($product['product_discount_fromdate'] != '0000-00-00' || $product['product_discount_fromdate'] != '0000-00-00')
											<p>
												<span class="text-muted">{{ \Lang::get('webshoppack::myProducts.product_discount_price') }}:</span>
												{{ $productService->getBaseAmountToDisplay($product['product_discount_price'], $product['product_price_currency']) }}
                                            </p>
											<p>
												<span class="text-muted">{{ \Lang::get('webshoppack::common.from') }}:</span> {{ date('M j, Y', strtotime($product['product_discount_fromdate'])) }}
												<span class="text-muted">{{ \Lang::get('webshoppack::common.to') }}:</span> {{ date('M j, Y', strtotime($product['product_discount_todate'])) }}
											</p>
										@else
											<p><span class="text-muted">{{ \Lang::get('webshoppack::myProducts.product_discount_price') }}:</span>
											{{ \Lang::get('webshoppack::myProducts.not_applicable') }}</p>
										@endif
									@endif
							</td>
							<td class="store-lists">
								<p  class="text-info">{{ $product['product_sold'] }}</p>
							</td>
							<td>
									<?php
										$display_product_status = "";
										$lbl_class ="";
										if($product['product_status'] == "ToActivate") {
											$display_product_status =  \Lang::get('webshoppack::product.pending_approval_label');
											$lbl_class ="label-warning";
										}
										elseif($product['product_status'] == "NotApproved") {
											$display_product_status =  \Lang::get('webshoppack::product.rejected_label');
											$lbl_class ="label-danger";
										}
										elseif($product['product_status'] == "Ok") {
											$display_product_status =  \Lang::get('webshoppack::product.active');
											$lbl_class ="label-success";
										}
										else {
											$display_product_status = $product['product_status'];
											$lbl_class ="label-info";
										}
									?>
									<span class="label {{ $lbl_class }}">{{ $display_product_status }}</span>
								</td>
								<td>
									<p><i class="fa fa-eye texticon-link"></i> <a href="{{ $view_url }}">{{ \Lang::get('webshoppack::product.product_view')  }}</a></p>{{-- Config::get('webshoppack::product_list_view') --}}
									<p><i class="fa fa-edit text-info"></i> <a href="{{ Config::get('webshoppack::product_list_edit').$product['id'] }}" class="text-info">{{ \Lang::get('webshoppack::product.product_edit')  }}</a></p>
									<p><i class="fa fa-trash-o text-danger"></i> <a href="javascript:void(0)" onclick="doAction('{{ $product['id'] }}', 'delete')" class=" text-danger">
									{{ \Lang::get('webshoppack::product.product_delete')  }}</a></p>

								</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td colspan="6"><p class="alert alert-info">{{ \Lang::get('webshoppack::product.products_not_found_msg') }}</p></td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
		@if(count($product_list) > 0)
			{{ $product_list->appends(array('search_product_code' => Input::get('search_product_code'), 'search_product_name' => Input::get('search_product_name'), 'search_product_category' => Input::get('search_product_category'), 'search_product_status' => Input::get('search_product_status'), 'srchproduct_submit' => Input::get('srchproduct_submit')))->links() }}
    	@endif
	{{ Form::close() }}
	{{ Form::open(array('id'=>'productsActionfrm', 'method'=>'post', 'url' => URL::to(Config::get('webshoppack::myProducts').'/deleteproduct'))) }}
		{{ Form::hidden('p_id', '', array('id' => 'p_id')) }}
     	{{ Form::hidden('product_action', '', array('id' => 'product_action')) }}
    {{ Form::close() }}
    <div id="dialog-product-confirm" title="" style="display:none;">
	    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><span id="dialog-product-confirm-content"></span></p>
	</div>
	@else
		<div class="alert alert-warning">
	       {{ \Lang::get('webshoppack::product.list_empty') }}
	    </div>
	@endif

@endif
@stop
@section('script_content')
	<script type="text/javascript">
	      $('.fn_clsDropSearch').click(function() {
	        $('#search_holder').slideToggle(500);
	        // toggle open/close symbol
	        var span_elm = $('.fn_clsDropSearch i');
	        if(span_elm.hasClass('fa fa-caret-up')) {
	            $('.fn_clsDropSearch').html('{{ \Lang::get('webshoppack::product.show_search_filters') }} <i class="fa fa-caret-down"></i>');
	        } else {
	            $('.fn_clsDropSearch').html('{{ \Lang::get('webshoppack::product.hide_search_filters') }} <i class="fa fa-caret-up"></i>');
	        }
	        return false;
	    });

	    function doAction(p_id, selected_action)
		{
			if(selected_action == 'delete')
			{
				$('#dialog-product-confirm-content').html('{{ \Lang::get('webshoppack::myProducts.product_confirm_delete') }}');
			}
			else if(selected_action == 'feature')
			{
				$('#dialog-product-confirm-content').html('{{ \Lang::get('webshoppack::myProducts.product_confirm_featured') }}');
			}
			else if(selected_action == 'unfeature')
			{
				$('#dialog-product-confirm-content').html('{{ \Lang::get('webshoppack::myProducts.product_confirm_unfeatured') }}');
			}
			$("#dialog-product-confirm").dialog({ title: '{{ \Lang::get('webshoppack::myProducts.my_products_title') }}', modal: true,
				buttons: {
					"{{ \Lang::get('webshoppack::common.yes') }}": function() {
						$(this).dialog("close");
						$('#product_action').val(selected_action);
						$('#p_id').val(p_id);
						document.getElementById("productsActionfrm").submit();
					}, "{{ \Lang::get('webshoppack::common.cancel') }}": function() { $(this).dialog("close"); }
				}
			});

			return false;
		}

		$(".fn_changeStatus").fancybox({
	        maxWidth    : 800,
	        maxHeight   : 430,
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