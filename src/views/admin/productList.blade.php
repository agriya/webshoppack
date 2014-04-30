@extends(Config::get('webshoppack::package_admin_layout'))
@section('content')
	@if(Session::has('error_message') && Session::get('error_message') != '')
        <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
        <?php Session::forget('error_message'); ?>
    @endif
    @if(Session::has('success_message') && Session::get('success_message') != '')
        <div class="alert alert-success">{{ Session::get('success_message') }}</div>
        <?php Session::forget('success_message'); ?>
    @endif
    <div class="row">
		<div class="col-md-12">
			{{ Form::open(array('id'=>'productssSearchfrm', 'method'=>'get','class' => 'form-horizontal search-bar' )) }}
				<div class="widget-box transparent @if(!Input::has('search_submit'))collapsed @endif">
					<div class="widget-header widget-header-flat admin-searchbar">
						<div class="widget-toolbar">
							<a href="#" data-action="collapse">
								<i class="icon-chevron-down"></i>
								<span>{{ trans('webshoppack::admin/productList.product_search_title') }}</span>
							</a>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main no-padding">
							<div id="search_holder">
								<div id="selSrchScripts" class="border-type1">
									<div class="row">
										<div class="clearfix">
											<fieldset class="col-xs-6">
												<div class="form-group">
													{{ Form::label('search_product_id_from', trans('webshoppack::admin/productList.product_search_product_id'), array('class' => 'col-sm-4 control-label')) }}
													<div class="col-sm-8">
														{{ Form::text('search_product_id_from', Input::get("search_product_id_from"), array('class' => 'col-xs-10 col-sm-9 form-group', 'placeholder' => trans('webshoppack::admin/productList.product_search_product_id_from'))) }}
														{{ Form::text('search_product_id_to', Input::get("search_product_id_to"), array('class' => 'col-xs-10 col-sm-9', 'placeholder' => trans('webshoppack::admin/productList.product_search_product_id_to'))) }}
														<label class="error" for="search_product_id_from" generated="true">{{$errors->first('search_product_id_from')}}</label>
													</div>
												</div>

												<div class="form-group">
													{{ Form::label('search_product_name', trans('webshoppack::admin/productList.product_search_product_name'), array('class' => 'col-sm-4 control-label')) }}
													<div class="col-sm-8">
														{{ Form::text('search_product_name', Input::get("search_product_name"), array('class' => 'col-xs-10 col-sm-9')) }}
														<label class="error" for="search_product_name" generated="true">{{$errors->first('search_product_name')}}</label>
													</div>
												</div>

												<div class="form-group">
													{{ Form::label('search_product_category', trans('webshoppack::admin/productList.product_search_product_category'), array('class' => 'col-sm-4 control-label')) }}
													<div class="col-sm-8">
														{{ Form::select('search_product_category', $d_arr['category_arr'], Input::get("search_product_category"), array('class' => 'col-xs-10 col-sm-9')) }}
														<label class="error" for="search_product_category" generated="true">{{$errors->first('search_product_category')}}</label>
													</div>
												</div>
											</fieldset>

											 <fieldset class="col-xs-6">
												<div class="form-group">
													{{ Form::label('search_featured_product', trans('webshoppack::admin/productList.product_search_featured_status'), array('class' => 'col-sm-4 control-label')) }}
													<div class="col-sm-8">
														{{ Form::select('search_featured_product', $d_arr['feature_arr'], Input::get("search_featured_product"), array('class' => 'col-xs-10 col-sm-9')) }}
														<label class="error" for="search_featured_product" generated="true">{{$errors->first('search_featured_product')}}</label>
													</div>
												</div>

												<div class="form-group">
													{{ Form::label('search_product_author', trans('webshoppack::admin/productList.product_search_product_author'), array('class' => 'col-sm-4 control-label')) }}
													<div class="col-sm-8">
														{{ Form::text('search_product_author', Input::get("search_product_author"), array('class' => 'col-xs-10 col-sm-9')) }}
														<label class="error" for="search_product_author" generated="true">{{$errors->first('search_product_author')}}</label>
													</div>
												</div>

												<div class="form-group">
													{{ Form::label('search_product_status', trans('webshoppack::admin/productList.product_search_status'), array('class' => 'col-sm-4 control-label')) }}
													<div class="col-sm-8">
														{{ Form::select('search_product_status', $d_arr['status_arr'], Input::get("search_product_status"), array('class' => 'col-xs-10 col-sm-9')) }}
														<label class="error" for="search_product_status" generated="true">{{$errors->first('search_product_status')}}</label>
													</div>
												</div>
											</fieldset>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-2 pad-left4">
												<button type="submit" name="search_submit" value="search_submit" class="btn btn-purple btn-sm">
												{{ trans("webshoppack::common.search") }} <i class="icon-search bigger-110"></i></button>
												<button type="reset" name="search_reset" value="search_reset" class="btn btn-sm" onclick="javascript:location.href='{{ Request::url() }}'">
												<i class="icon-undo bigger-110"></i> {{ trans("webshoppack::common.reset")}}</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div><!--/search filter-main-->
					</div><!--/search filter-body-->
				</div><!--/search filter-box-->
            {{ Form::close() }}

		    <div class="message-navbar mb20">
            	<a href="{{ URL:: to(Config::get('webshoppack::admin_uri').'/add') }}" class="btn btn-info btn-xs pull-right"><i class="icon-plus-sign"></i> {{trans('webshoppack::admin/productList.add_product')}}</a>
                <h1 class="admin-title blue bigger-150">{{ $d_arr['product_list_title'] }}</h1>
            </div><!--/.page-header-->

            {{ Form::open(array('id'=>'productsListfrm', 'method'=>'get','class' => 'form-horizontal form-request overflow-auto' )) }}
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thin-border-bottom">
                        <tr>
                            <th><div class="wid-100">{{ trans('webshoppack::admin/productList.product_product_code') }}</div></th>
                            <th class="col-sm-2"><div class="wid-140">{{ trans('webshoppack::admin/productList.product_product_name') }}</div></th>
                            <th><div class="wid-100">{{ trans('webshoppack::admin/productList.product_author') }}</div></th>
                            <th class="col-sm-3"><div class="wid-140">{{ trans('webshoppack::admin/productList.product_price') }}</div></th>
                            <th>{{ trans('webshoppack::admin/productList.product_featured') }}</th>
                            <th>{{ trans('webshoppack::admin/productList.product_status') }}</th>
                            <th class="col-sm-2"><div class="wid-140">{{ trans('webshoppack::admin/productList.product_action') }}</div></th>
                        </tr>
                    </thead>
                    <tbody>
                    	@if(count($products_arr) > 0)
                    		<?php
                    			$p_service = new Agriya\Webshoppack\ProductService();
                    		?>
                    		@foreach($products_arr as $key => $prd)
                    			<tr>
									<?php
	                    				$product_view_url = $p_service->getAdminProductViewURL($prd->id, $prd);
										$user_details = Agriya\Webshoppack\CUtil::getUserDetails($prd->product_user_id);
	                    				$category_arr = $service_obj->getProductCategoryArr($prd->product_category_id);
	                    			?>
									<td>{{ HTML::link($product_view_url, $prd->product_code, array('target' => '_blank')) }}</td>
									<td>
										<p>{{ HTML::link($product_view_url, $prd->product_name, array('target' => '_blank')) }}</p>
										<p class="text-muted"> {{{ implode(' / ', $category_arr) }}} </p>
									</td>
									<td>{{ $user_details['display_name'] }}</td>
									<td>
										@if($prd->is_free_product == 'Yes')
											<span class="text-success">{{ trans('webshoppack::admin/productList.product_free') }}</span>
										@else
											<?php
												$product_price = Agriya\Webshoppack\CUtil::getBaseAmountToDisplay($prd->product_price, $prd->product_price_currency);
												$discount_price = 0;
												if($prd->product_discount_todate != '0000-00-00' || $prd->product_discount_fromdate != '0000-00-00')
												{
													$discount_from_date = date('M j, Y', strtotime($prd->product_discount_fromdate));
													$discount_end_date = date('M j, Y', strtotime($prd->product_discount_todate));
													$discount_price = Agriya\Webshoppack\CUtil::getBaseAmountToDisplay($prd->product_discount_price, $prd->product_price_currency);
												}
											?>
											<p>
												<span class="pull-left">&raquo;</span>
												<span class="block ml15">
													<span class="text-muted">{{ trans('webshoppack::admin/productList.product_product_price') }}:</span>
													{{ $product_price }}
													@if($prd->product_price_currency != 'USD')
														($ {{ $prd->product_price_usd }} )
													@endif
												</span>
											</p>
											@if(!empty($discount_price))
												<div>
													<p>
														<span class="pull-left">&raquo;</span>
														<span class="block ml15">
															<span class="text-muted">{{ trans('webshoppack::admin/productList.product_discount_price') }}:</span>
															{{ $discount_price }}
															@if($prd->product_price_currency != 'USD')
																($ {{ $prd->product_discount_price_usd }} )
															@endif
														</span>
													</p>
													<p>
														<span class="pull-left">&raquo;</span>
														<span class="block ml15">
														<span class="text-muted">{{ trans('webshoppack::admin/productList.product_discount_from') }}:</span>
														{{ $discount_from_date }}
														<span class="text-muted">{{ trans('webshoppack::admin/productList.product_discount_to') }}:</span>
														{{ $discount_end_date }}</span>
													</p>
												</div>
											@endif
										@endif
									</td>
									<td>
										<?php
											if(count($prd) > 0)
											{
												if($prd['is_featured_product'] == 'Yes')
												{
													$lbl_class = "badge badge-success";
												}
												elseif($prd['is_featured_product'] == 'No')
												{
													$lbl_class = "badge badge-danger";
												}
											}
										?>
										<span class="badge {{ $lbl_class }}">{{ $prd->is_featured_product }}</span>
										@if($d_arr['allow_to_change_status'] && $prd->product_status == 'Ok' && $prd->is_featured_product == 'No')
											<p><a href="javascript:void(0)" onclick="doAction('{{ $prd->id }}', 'feature')">{{ trans('webshoppack::admin/productList.product_set_featured')  }}
											</a></p>
										@endif
									</td>
									<td>
										<?php
											if(count($prd) > 0)
												{
												if($prd['product_status'] == 'Ok')
													{
														$lbl_class = "label-success";
													}
												elseif($prd['product_status'] == 'ToActivate')
													{
														$lbl_class = "label-warning";
													}
												elseif($prd['product_status'] == 'NotApproved')
													{
														$lbl_class = "label-danger";
													}
												elseif($prd['product_status'] == 'Draft')
													{
														$lbl_class = "label-info";
													}
											}
										?>
										<span class="label {{ $lbl_class }}">{{ $service_obj->product_status_arr[$prd->product_status] }}</span>
									</td>
									<td class="hidden-sm btn-group">
										<!--<div class="action-buttons">-->
                                        	<a href="{{ URL:: to(Config::get('webshoppack::admin_uri').'/add?id='.$prd->id) }}" class="btn btn-xs btn-info" title="{{ trans('webshoppack::admin/productList.product_edit') }}">
											<i class="icon-edit bigger-120"></i></a>
                                        	<a href="{{$product_view_url}}" class="btn btn-success btn-xs" title="{{ trans('webshoppack::admin/productList.product_view') }}">
											<i class="icon-eye-open bigger-130"></i></a>
                                        	@if($d_arr['allow_to_change_status'])
	                                        	@if($prd->product_status == 'ToActivate')
													<a class="fn_changeStatus btn btn-xs btn-primary" href="{{ URL::to(Config::get('webshoppack::admin_uri').'/list/change-status?p_id='.$prd->id) }}" title="{{ trans('webshoppack::admin/productList.change_status') }}"><i class="icon-cog bigger-120"></i></a>
	                                        	@endif
	                                        	@if($prd->product_status == 'Ok' && $prd->is_featured_product == 'Yes')
													<a href="javascript:void(0)" onclick="doAction('{{ $prd->id }}', 'unfeature')" class="btn btn-xs btn-warning" title="remove">
													<i class="icon-remove bigger-120"></i></a>
	                                        	@endif
	                                        	<a href="javascript:void(0)" onclick="doAction('{{ $prd->id }}', 'delete')" class="btn btn-xs btn-danger">
												<i class="icon-trash bigger-120"></i></a>
	                                        @endif
                                        <!--</div>-->
									</td>
								</tr>
                            @endforeach
                        @else
                            <tr>
                            	<td colspan="7">
                                	<p class="alert alert-info">{{ trans('webshoppack::admin/productList.product_no_products_to_list') }}</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="text-right">
                	{{ $products_arr->appends( array('search_product_id_from' => Input::get('search_product_id_from'), 'search_product_id_to' => Input::get('search_product_id_to'), 'search_product_name' => Input::get('search_product_name'), 'search_product_category' => Input::get('search_product_category'), 'search_featured_product' => Input::get('search_featured_product'), 'search_product_author' => Input::get('search_product_author'), 'search_product_status' => Input::get('search_product_status'),'serach_submit' =>'search_submit'))->links() }}
				</div>
             {{ Form::close() }}

			 {{ Form::open(array('id'=>'productsActionfrm', 'method'=>'post', 'url' => URL::to(Config::get('webshoppack::admin_uri').'/list/product-action'))) }}
             	{{ Form::hidden('p_id', '', array('id' => 'p_id')) }}
             	{{ Form::hidden('product_action', '', array('id' => 'product_action')) }}
             {{ Form::close() }}
		</div>
	</div>
<div id="dialog-product-confirm" title="" style="display:none;">
    <span class="ui-icon ui-icon-alert"></span>
	<span id="dialog-product-confirm-content" class="show ml15"></span>
</div>
@stop
@section('script_content')
	<script type="text/javascript">
	@if($d_arr['allow_to_change_status'])
		function doAction(p_id, selected_action)
		{
			if(selected_action == 'delete')
			{
				$('#dialog-product-confirm-content').html('{{ trans('webshoppack::admin/productList.product_confirm_delete') }}');
			}
			else if(selected_action == 'feature')
			{
				$('#dialog-product-confirm-content').html('{{ trans('webshoppack::admin/productList.product_confirm_featured') }}');
			}
			else if(selected_action == 'unfeature')
			{
				$('#dialog-product-confirm-content').html('{{ trans('webshoppack::admin/productList.product_confirm_unfeatured') }}');
			}
			$("#dialog-product-confirm").dialog({ title: '{{ trans('webshoppack::admin/productList.product_head') }}', modal: true,
				buttons: {
					"{{ trans('webshoppack::common.yes') }}": function() {
						$(this).dialog("close");
						$('#product_action').val(selected_action);
						$('#p_id').val(p_id);
						document.getElementById("productsActionfrm").submit();
					}, "{{ trans('webshoppack::common.cancel') }}": function() { $(this).dialog("close"); }
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
	@endif
	</script>
@stop