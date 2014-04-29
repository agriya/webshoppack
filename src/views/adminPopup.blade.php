<!DOCTYPE html>
<html>
    <head>
		<head>
		<title>{{ \Config::get('webshoppack::index_page_title') }}</title>
		<meta name="keywords" content="{{ \Config::get('webshoppack::index_page_meta_keywords') }}" />
		<meta name="description" content="{{ \Config::get('webshoppack::index_page_meta_description') }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="{{ URL::asset('packages/agriya/webshoppack/images/header/favicon/favicon.ico') }}" type="image/x-icon" />

        <!--basic styles-->
		<link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/admin/bootstrap.min.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/admin/font-awesome.min.css') }}" />
		<!--[if IE 7]>
		  <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/admin/font-awesome-ie7.min.css') }}" />
		<![endif]-->
		<!--page specific plugin styles-->
		<!--fonts-->
		<link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/admin/ace-fonts.css') }}" />
		<!--ace styles-->
		<link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/admin/ace.min.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/admin/ace-responsive.min.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/admin/ace-skins.min.css') }}" />
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/admin/datepicker.css') }}" />
		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/admin/ace-ie.min.css') }}" />
		<![endif]-->
		<!--inline styles related to this page-->
		<!--ace settings handler-->
		<script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/ace-extra.min.js')}}"></script>

		<link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/jQuery_plugins/ui-lightness/jquery-ui-1.10.3.custom.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/jQuery_plugins/jquery.fancyBox-v2.1.5-0/jquery.fancybox.css') }}">

        <?php /*?><link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/jQuery_plugins/jquery.fancybox-1.3.4/jquery.fancybox.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/jQuery_plugins/ui-lightness/jquery-ui-1.10.3.custom.css') }}"><?php */?>
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/core/embed_fonts.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/core/form.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/core/admin.css') }}">
        <!--<link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/jQuery_plugins/smoothness/jquery-ui.css') }}">-->

        <!--[if lte IE 9]>
        	<script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/html5shiv.js') }}"></script>
        <![endif]-->

        @yield('includescripts')
        <script>
			var currency_code = 'INR';
		</script>
    </head>
    <body class="popup-container">
    	<!--basic scripts-->
		<!--[if !IE]>-->
		<script type="text/javascript">
			var jmin3="{{ URL::asset('packages/agriya/webshoppack/js/admin/jquery-2.0.3.min.js') }}";
			window.jQuery || document.write("<script src='"+jmin3+"'>"+"<"+"/script>");
		</script>

		<!--<![endif]-->

		<!--[if IE]>
        <script type="text/javascript">
        	var jmin2="{{ URL::asset('packages/agriya/webshoppack/js/admin/jquery-1.10.2.min.js')}}";
        	window.jQuery || document.write("<script src='jmin2'>"+"<"+"/script>");
        </script>
        <![endif]-->

		<script type="text/javascript">
			var jmobile="{{ URL::asset('packages/agriya/webshoppack/js/admin/jquery.mobile.custom.min.js')}}";
			if("ontouchend" in document) document.write("<script src='"+jmobile+"'>"+"<"+"/script>");
		</script>
		<script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/bootstrap.min.js')}}"></script>

		<!--page specific plugin scripts-->

		<!--[if lte IE 8]>
		  <script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/excanvas.min.js')}}"></script>
		<![endif]-->

		<script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/jquery-ui-1.10.3.custom.min.js')}}"></script>
		<script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/jquery.ui.touch-punch.min.js')}}"></script>
		<script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/jquery.slimscroll.min.js')}}"></script>
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/jquery-ui-1.10.3.full.min.js')}}"></script>
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/jquery.validate.min.js') }}"></script>
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/jquery.inputlimiter.1.3.1.min.js')}}"></script>
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/bootstrap-datepicker.min.js')}}"></script>		<!--ace scripts-->
		<script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/ace-elements.min.js')}}"></script>
		<script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/ace.min.js')}}"></script>

        <script src="{{ URL::asset('packages/agriya/webshoppack/js/functions.js') }}"></script>
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/jquery.fancybox.pack.js') }}"></script>

         <section>@yield('content')</section>
         @yield("script_content")

    </body>
</html>