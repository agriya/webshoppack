<html>
	<head>
		<title>{{ \Config::get('webshoppack::index_page_title') }}</title>
		<meta name="description" content="{{ \Config::get('webshoppack::index_page_meta_description') }}" />
		<meta name="keywords" content="{{ \Config::get('webshoppack::index_page_meta_keywords') }}" />


		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Favicons
		================================================== -->
		<link rel="shortcut icon" href="{{ URL::asset('packages/agriya/webshoppack/images/header/favicon/favicon.ico') }}">

        <!-- CSS
		================================================== -->
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/jQuery_plugins/ui-lightness/jquery-ui-1.10.3.custom.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/jQuery_plugins/jquery.fancyBox-v2.1.5-0/jquery.fancybox.css') }}">

	    <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/bootstrap/bootstrap.min.css') }}">        <!-- // Version 3.1.1  -->
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/bootstrap/bootstrap-theme.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/bootstrap/font-awesome.min.css') }}">     <!-- // Version 4.0.3  -->

        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/core/embed_fonts.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/core/base.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/core/form.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/core/mobile.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/admin/datepicker.css') }}" />

        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/js/lib/jcarousel-0.3.0/css/jcarousel.connected-carousels.css') }}" />

        <script language="javascript">
			var mes_required = "{{ Lang::get('webshoppack::common.required') }}";
			var page_name = "";
		</script>
	</head>
	<body>
		<article class="article-container">
			<section class="container">
	            <div class="row">
	            	<div id="selLoading" class="loading" style="display:none;">
		                <img src="{{URL::asset('packages/agriya/webshoppack/images/general/bg_opac.png')}}" height="100%" width="100%" />
		                <div class="loading-cont">
		                    <img src="{{ URL::asset('packages/agriya/webshoppack/images/general/loader.gif') }}" />
		                    <p><strong>{{ trans('webshoppack::common.loading') }}</strong></p>
		                </div>
			        </div>

			        @if(Config::get('webshoppack::use_package_header'))
			        	<h1>{{ Config::get('webshoppack::package_name') }}</h1>
			        @else
						@if(Config::get('webshoppack::set_package_header_path') != '')
							@include(Config::get('webshoppack::set_package_header_path'))
						@endif
			    	@endif

	                <div class="col-md-12" role="main">
	                	@yield('content')
	                </div>
	            </div>
	        </section>
	        @if(Config::get('webshoppack::use_package_footer'))
        	<div class="footer">&copy; {{ Config::get('webshoppack::package_name') }} Inc. All rights reserved.</div>
        @else
			@if(Config::get('webshoppack::set_package_footer_path') != '')
				@include(Config::get('webshoppack::set_package_footer_path'))
			@endif
    	@endif
		</article>
		<!-- JS
		================================================== -->
    	<script src="{{ URL::asset('packages/agriya/webshoppack/js/jquery-1.11.0.min.js') }}"></script>
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/jquery-ui-1.10.3.custom.min.js') }}"></script>
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/jquery.validate.min.js') }}"></script>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/bootstrap/bootstrap.min.js') }}"></script>

        <script src="{{ URL::asset('packages/agriya/webshoppack/js/jquery.fancybox.pack.js') }}"></script>
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/functions.js') }}"></script>
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/lib/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/jobs.js') }}"></script>
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/bootstrap-datepicker.js') }}"></script>
        @yield("script_content")
	</body>
</html>