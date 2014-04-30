<div class="col-lg-3">
	<div class="aside-bar">
		<div class="title-block">
            @if($subcat)
                <h3>{{ Lang::get('webshoppack::product.sub_categories_title') }}</h3>
            @else
                <h3>{{ Lang::get('webshoppack::product.categories_title') }}</h3>
            @endif
        </div>
		 @if(count($cat_list) > 0)
		    <ul class="list-unstyled no-mar clearfix">
		        @foreach($cat_list AS $cat)
		            <li><i class="fa fa-angle-right"></i><span><a href="{{$cat['cat_link']}}" title="{{{ $cat['category_name'] }}} ({{ $cat['product_count'] }})">{{{ $cat['category_name'] }}} ({{ $cat['product_count'] }})</a></span></li>
		        @endforeach
		    </ul>
		@else
			<p class="alert alert-info">{{ Lang::get('webshoppack::product.category_not_found') }}</p>
		@endif
    </div>

    <div class="aside-bar">
        {{ Form::open(array('url' => Request::url(), 'method' => 'get', 'class' => 'form-horizontal',  'id' => 'productSearchfrm', 'name' => 'productSearchfrm')) }}
            <div class="title-block">
                <h3>{{ Lang::get('webshoppack::product.refine_search_title') }}</h3>
            </div>
	    	@if(count($cat_list) > 0)
				<ul class="mb20 list-unstyled no-mar clearfix">
					@foreach($cat_list_arr as $key => $val)
                        <li class="checkbox">
                        	<?php
                        		$cat_search = array();
                        		if(Input::get('cat_search') != "")
                        		{
									$cat_search = Input::get('cat_search');
								}
                        	?>
                            {{ Form::checkbox('cat_search[]', $key, (in_array($key, $cat_search))? true : false, array("id" => $key)) }}
                            {{ Form::label($key, $val) }}
                        </li>
                    @endforeach
		        </ul>
	    	@endif
	    	<p>{{ Form::text('tag_search', Input::get('tag_search'), array('class' => 'form-control', 'placeholder' => Lang::get('webshoppack::product.keyword_search_title'))); }}</p>
	        <p>{{ Form::text('author_search', Input::get('author_search'), array('class' => 'form-control', 'placeholder' => Lang::get('webshoppack::product.owner_search_title'))); }}</p>
	        <p class="mb30">{{ Form::text('shop_search', Input::get('shop_search'), array('class' => 'form-control', 'placeholder' => Lang::get('webshoppack::product.shop_search_title'))); }}</p>
			<h3 class="title-four">{{ Lang::get('webshoppack::product.price_range_title') }}</h3>
			<div class="mb20">
				<p>USD {{ Form::text('price_range_start', Input::get('price_range_start'), array('class' => 'form-control')); }}</p>
				<p>{{ Lang::get('webshoppack::common.to') }} USD {{ Form::text('price_range_end', Input::get('price_range_end'), array('class' => 'form-control')); }}</p>
			</div>
            <button type="submit" name="search_products" value="search_products" class="btn btn-primary custom-btn1"><i class="icon-ok bigger-110"></i>{{ Lang::get('webshoppack::common.search') }}</button>
            <button type="reset" name="reset_products" value="reset_products" class="btn btn-default custom-btn3" onclick="return clearForm(this.form);"><i class="icon-undo bigger-110"></i>{{ Lang::get('webshoppack::common.reset') }}</button>
		{{ Form::close() }}
    </div>
</div>