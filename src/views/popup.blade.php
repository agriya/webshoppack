<!DOCTYPE html>
<html lang="en">
    <head>
    	<!-- Basic Page Needs
		================================================== -->
		<meta charset="utf-8" />
		<title>{{ $header->getMetaTitle() }}</title>
		<meta name="author" content="Ahsan Technologies Pvt. Ltd., Chennai" />
		<meta name="keywords" content="{{ $header->getMetaKeyword() }}" />
		<meta name="description" content="{{ $header->getMetaDescription() }}" />

		@if($header->getViewCanonicalUrl() != "")
			<link rel="canonical" href="{{ $header->getViewCanonicalUrl() }}">
		@endif

		<!-- CSS
		================================================== -->
	    <!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('/css/embed_fonts.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('/css/base.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('/css/form.css') }}">

        <!-- Javascripts
		================================================== -->        
        <script src="{{ URL::asset('js/main.js') }}"></script>
        <script src="{{ URL::asset('js/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>
        <script src="{{ URL::asset('js/jquery-ui-1.10.3.custom.min.js') }}"></script>
        <script src="{{ URL::asset('js/functions.js') }}"></script>
        <script src="{{ URL::asset('js/jquery.fancybox.pack.js') }}"></script>
		<script src="{{ URL::asset('js/lib/jquery.inputlimiter.js')}}"></script>
        @yield('includescripts')
        <script>
			var site_name = "{{ Config::get('site.site_name') }}";
			var mes_required = "{{trans('auth/form.required')}}";
			var page_name = "";
			var invalid_price = '{{ trans("js.invalid_price") }}';
			var currency_code = 'INR';
			function setInputLimiterById(ident, char_limit)
			{
				$('#'+ident).inputlimiter({
					limit: char_limit,
					remText: '{{ trans("common.words_remaining_1")}} %n {{ trans("common.words_remaining_2")}} %s {{ trans("common.words_remaining_3")}}',
					limitText: '{{ trans("common.limitted_words_1")}} %n {{ trans("common.limitted_words_2")}}%s'
				});
			}			
		</script>
		<script src="{{ URL::asset('css/admin/assets/js/bootstrap.min.js')}}"></script>
    </head>
    <body class="popup-container">
        <section>@yield('content')</section>
    </body>
</html>