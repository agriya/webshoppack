<div class="container">
    <div class="navbar-header">
        <button data-target=".navbar-collapse" data-toggle="collapse" type="button" class="navbar-toggle">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <h1 class="navbar-brand">
            {{ Config::get('webshoppack::package_name') }}
        </h1>
    </div>

    <nav role="navigation" class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
        	<li><a href="{{ URL::to(Config::get('webshoppack::uri')) }}">Products</a></li>
            <li><a href="{{ URL::to(Config::get('webshoppack::shop_uri')) }}">Shops</a></li>
            <?php
				$cfg_is_logged_in = Config::get('webshoppack::is_logged_in');
				$cfg_is_admin = Config::get('webshoppack::is_admin');
			?>
            @if($cfg_is_logged_in())
                @if($cfg_is_admin())
                    <li><a href="{{ URL::to(Config::get('webshoppack::admin_uri').'/list') }}">Admin</a></li>
                @endif
	            <li class="dropdown">
					<a id="drop1" role="button" data-toggle="dropdown" href="{{ URL::to(Config::get('webshoppack::shop_uri')) }}">My Shop
					<i class="fa fa-caret-down text-muted"></i></a>
					<ul id="menu1" class="dropdown-menu" role="menu" aria-labelledby="drop1">
						<li><a href="{{ URL::to(Config::get('webshoppack::shop_uri').'/users/shop-details') }}">Shop Settings</a></li>
						<li><a href="{{ URL::to(Config::get('webshoppack::myProducts')) }}">Manage Products</a></li>
						<li><a href="{{ URL::to(Config::get('webshoppack::uri').'/add') }}">Add Product</a></li>
					</ul>
				</li>
            @endif
        </ul>
    </nav>
</div>