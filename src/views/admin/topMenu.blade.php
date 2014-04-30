<div class="sidebar" id="sidebar">
	<script type="text/javascript">
    try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
    </script>

    <ul class="nav nav-list">
        <li {{ (Request::is(Config::get('webshoppack::admin_uri').'/*')) ||  (Request::is(Config::get('webshoppack::admin_product_catalog_uri'))) ||  (Request::is(Config::get('webshoppack::admin_product_attr_uri'))) ? 'class="active"' : '' }}>
        	<a href="#" class="dropdown-toggle"><i class="icon-desktop"></i><span class="menu-text">Products</span><b class="arrow icon-angle-down"></b></a>
        	<ul class="submenu">
				<li {{ (Request::is(Config::get('webshoppack::admin_uri').'/*') ? 'class="active"' : '') }}>
	        	    <a href="{{ Url::to(Config::get('webshoppack::admin_uri').'/list')}}"><i class="icon-double-angle-right"></i> {{ trans('webshoppack::admin/productList.product_head') }}</a>
	            </li>
	            <li {{ (Request::is(Config::get('webshoppack::admin_product_catalog_uri')) ? 'class="active"' : '') }}>
	        	    <a href="{{ Url::to(Config::get('webshoppack::admin_product_catalog_uri'))}}"><i class="icon-double-angle-right"></i> {{ trans('webshoppack::common.product_categories') }}</a>
	            </li>
	            <li {{ (Request::is(Config::get('webshoppack::admin_product_attr_uri')) ? 'class="active"' : '') }}>
	        	    <a href="{{ Url::to(Config::get('webshoppack::admin_product_attr_uri'))}}"><i class="icon-double-angle-right"></i> {{ trans('webshoppack::common.product_attributes') }}</a>
	            </li>
	        </ul>
		</li>
		<li {{ (Request::is(Config::get('webshoppack::admin_shop_uri'))) ? 'class="active"' : '' }}>
			<a href="#" class="dropdown-toggle"><i class="icon-shopping-cart"></i><span class="menu-text">Shops</span><b class="arrow icon-angle-down"></b></a>
			<ul class="submenu">
				<li {{ (Request::is(Config::get('webshoppack::admin_shop_uri')) ? 'class="active"' : '') }}><a href="{{ URL::to(Config::get('webshoppack::admin_shop_uri')) }}"><i class="icon-double-angle-right"></i>Manage Shops</a></li>
			</ul>
		</li>
        <li>
        	@if (Sentry::check())
				<a href="{{ URL::to(\Config::get('webshopauthenticate::uri').'/logout') }}"><i class="icon-signout"></i><span class="menu-text">{{ trans('webshoppack::common.logout') }}</span></a>
			@endif
		</li>
    </ul><!--/.nav-list-->

    <div class="sidebar-collapse" id="sidebar-collapse">
    <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
    </div>

    <script type="text/javascript">
    try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
    </script>
</div>