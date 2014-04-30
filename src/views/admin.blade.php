<!DOCTYPE html>
<html>
    <head>
		<title>{{ \Config::get('webshoppack::index_page_title') }}</title>
		<meta name="keywords" content="{{ \Config::get('webshoppack::index_page_meta_keywords') }}" />
		<meta name="description" content="{{ \Config::get('webshoppack::index_page_meta_description') }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="{{ URL::asset('packages/agriya/webshoppack/images/header/favicon/favicon.ico') }}">

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
        <link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/js/lib/jcarousel-0.3.0/css/jcarousel.connected-carousels.css') }}" />
        <!--<link rel="stylesheet" href="{{ URL::asset('packages/agriya/webshoppack/css/jQuery_plugins/smoothness/jquery-ui.css') }}">-->

        <!--[if lte IE 9]>
        	<script src="{{ URL::asset('packages/agriya/webshoppack/js/admin/html5shiv.js') }}"></script>
        <![endif]-->

    </head>
    <body>
        <div id="selLoading" class="loading" style="display:none;">
            <img src="{{URL::asset('packages/agriya/webshoppack/images/general/bg_opac.png')}}" height="100%" width="100%" />
            <div class="loading-cont">
                <img src="{{ URL::asset('packages/agriya/webshoppack/images/general/loader.gif') }}" />
                <p><strong>{{ trans('webshoppack::common.loading') }}</strong></p>
            </div>
        </div>

        <div class="navbar navbar-default" id="navbar">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

            <div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left logo">
                	<h1><a href="{{ URL::to(Config::get('webshoppack::admin_uri').'/list') }}" class="navbar-brand"> {{ Config::get('webshoppack::package_name') }}</a>
                    </h1><!--/.brand-->
				</div><!-- /.navbar-header -->
	        </div>
    	</div>

        <div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div class="main-container-inner">
				<a class="menu-toggler" id="menu-toggler" href="#">
					<span class="menu-text"></span>
				</a>

				@include('webshoppack::admin/topmenu')

                <div class="main-content">
                    <div class="breadcrumbs" id="breadcrumbs">
                        <script type="text/javascript">
                            //try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                        </script>
                    </div>

                    <div class="page-content">
                    	<div class="row">
				            <div class="col-xs-12">
                		    	@yield('content')
                            </div>
                        </div>
                    </div>
                </div><!-- /.main-content -->
             </div><!-- /.main-container-inner -->



            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-primary">
                <i class="icon-double-angle-up icon-only bigger-110"></i>
            </a>
		</div><!--/.main-container-->


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
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/lib/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ URL::asset('packages/agriya/webshoppack/js/jquery.fancybox.pack.js') }}"></script>

    	<script type="text/javascript">
			jQuery(function($) {
				$('.easy-pie-chart.percentage').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
					var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
					var size = parseInt($(this).data('size')) || 50;
					$(this).easyPieChart({
						barColor: barColor,
						trackColor: trackColor,
						scaleColor: false,
						lineCap: 'butt',
						lineWidth: parseInt(size/10),
						animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
						size: size
					});
				})

				$('.sparkline').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
					$(this).sparkline('html', {tagValuesAttribute:'data-values', type: 'bar', barColor: barColor , chartRangeMin:$(this).data('min') || 0} );
				});


			  var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
			  var data = [
				{ label: "social networks",  data: 38.7, color: "#68BC31"},
				{ label: "search engines",  data: 24.5, color: "#2091CF"},
				{ label: "ad campaings",  data: 8.2, color: "#AF4E96"},
				{ label: "direct traffic",  data: 18.6, color: "#DA5430"},
				{ label: "other",  data: 10, color: "#FEE074"}
			  ]




			  var $tooltip = $("<div class='tooltip top in hide'><div class='tooltip-inner'></div></div>").appendTo('body');
			  var previousPoint = null;

			  placeholder.on('plothover', function (event, pos, item) {
				if(item) {
					if (previousPoint != item.seriesIndex) {
						previousPoint = item.seriesIndex;
						var tip = item.series['label'] + " : " + item.series['percent']+'%';
						$tooltip.show().children(0).text(tip);
					}
					$tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
				} else {
					$tooltip.hide();
					previousPoint = null;
				}

			 });



				var d1 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.5) {
					d1.push([i, Math.sin(i)]);
				}

				var d2 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.5) {
					d2.push([i, Math.cos(i)]);
				}

				var d3 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.2) {
					d3.push([i, Math.tan(i)]);
				}


				$('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('.tab-content')
					var off1 = $parent.offset();
					var w1 = $parent.width();

					var off2 = $source.offset();
					var w2 = $source.width();

					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}


				$('.dialogs,.comments').slimScroll({
					height: '300px'
			    });


				//Android's default browser somehow is confused when tapping on label which will lead to dragging the task
				//so disable dragging when clicking on label
				var agent = navigator.userAgent.toLowerCase();
				if("ontouchstart" in document && /applewebkit/.test(agent) && /android/.test(agent))
				  $('#tasks').on('touchstart', function(e){
					var li = $(e.target).closest('#tasks li');
					if(li.length == 0)return;
					var label = li.find('label.inline').get(0);
					if(label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation() ;
				});

				$('#tasks').sortable({
					opacity:0.8,
					revert:true,
					forceHelperSize:true,
					placeholder: 'draggable-placeholder',
					forcePlaceholderSize:true,
					tolerance:'pointer',
					stop: function( event, ui ) {//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
						$(ui.item).css('z-index', 'auto');
					}
					}
				);
				$('#tasks').disableSelection();
				$('#tasks input:checkbox').removeAttr('checked').on('click', function(){
					if(this.checked) $(this).closest('li').addClass('selected');
					else $(this).closest('li').removeClass('selected');
				});
			});
		</script>
        @yield("script_content")
        <div class="footer">&copy; {{ Config::get('webshoppack::package_name') }} Inc. All rights reserved.</div>
    </body>
</html>