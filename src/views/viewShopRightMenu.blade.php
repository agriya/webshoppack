<aside class="col-lg-3">
	<div class="aside-bar">
        <div class="title-block">
            <h3>{{{ $shop_details['shop_name'] }}}</h3>
        </div>
		<p>{{{ $shop_details['shop_slogan'] }}}</p>
    </div>

    @if(count($section_details) > 0)
		<div class="aside-bar">
	    	<div class="title-block">
            	<h3>{{ trans("webshoppack::shop.shop_product_sections") }}</h3>
            </div>
		    <ul class="list-unstyled no-mar clearfix">
		    	<li><i class="fa fa-angle-right"></i><span><a href="{{$default_section_details['section_view_url']}}" title="{{$default_section_details['section_name']}}">{{$default_section_details['section_name']}} ({{ $default_section_details['section_count'] }})</a></span></li>
		        @foreach($section_details AS $section)
		        	<?php
		        		$section_view_url = $shop_view_url."?section_id=".$section['id'];
		        	?>
		            <li><i class="fa fa-angle-right"></i><span><a href="{{$section_view_url}}" title="{{{ $section['section_name'] }}}">{{{ $section['section_name'] }}} ({{ $section['section_count'] }})</a></span></li>
		        @endforeach
		    </ul>
	    </div>
	@endif

	<div class="aside-bar">
        <div class="title-block">
            <h3>{{ trans("webshoppack::shop.shop_owner") }}</h3>
        </div>
		<?php
            $user_details = Agriya\Webshoppack\CUtil::getUserDetails($shop_details['user_id']);
        ?>
        <a href='{{$user_details['profile_url']}}' class="light-link">{{ $user_details['display_name'] }}</a>
		<ul class="list-unstyled no-mar clearfix">
        	<li><i class="fa fa-angle-right"></i><span><a href="{{$user_details['profile_url']}}" title="">{{ trans("webshoppack::shop.profile_label") }}</a></span></li>
        	@if(Config::get("webshoppack::is_logged_in"))
				@if(!$viewShopServiceObj->current_user)
	        		<li><i class="fa fa-angle-right"></i><span><a href="{{ Url::to(Config::get('webshoppack::shop_uri').'/user/message/add/'.$user_details['user_code']) }}" class="fn_signuppop">{{ trans("webshoppack::shop.contact_label") }}</a></span></li>
	        	@endif
            @endif
		</ul>
	</div>
</aside>