@extends('webshoppack::admin')
@section('content')
	@if (Session::has('success_message') && Session::get('success_message') != "")
        <div class="alert alert-success">{{	Session::get('success_message') }}</div>
    @endif
    @if (Session::has('error_message') && Session::get('error_message') != "")
        <div class="alert alert-danger">{{	Session::get('error_message') }}</div>
    @endif

	<div class="row">
        <div class="col-md-12">
        {{ Form::open(array('id'=>'MemberSearchfrm', 'method'=>'get','class' => 'form-horizontal search-bar' )) }}
        <div class="widget-box transparent @if(!Input::has('search_members'))collapsed @endif">
            <div class="widget-header widget-header-flat admin-searchbar">
                <div class="widget-toolbar">
                    <a href="#" data-action="collapse">
                        <i class="icon-chevron-down"></i>
                        <span>{{ trans('webshoppack::admin/manageShops.shoplist_search_members') }}</span>
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
                                            {{ Form::label('shop_name', trans('webshoppack::admin/manageShops.shoplist_shop_name'), array('class' => 'col-sm-4 control-label')) }}
                                            <div class="col-sm-8">
                                                {{ Form::text('shop_name', Input::get("shop_name"), array('class' => 'col-xs-10 col-sm-9')) }}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('user_name', trans('webshoppack::admin/manageShops.shoplist_user_name'), array('class' => 'col-sm-4 control-label')) }}
                                            <div class="col-sm-8">
                                                {{ Form::text('user_name', Input::get("user_name"), array('class' => 'col-xs-10 col-sm-9')) }}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('user_email', trans('webshoppack::admin/manageShops.shoplist_user_email'), array('class' => 'col-sm-4 control-label')) }}
                                            <div class="col-sm-8">
                                                {{ Form::text('user_email', Input::get("user_email"), array('class' => 'col-xs-10 col-sm-9')) }}
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="col-xs-6">
                                        <div class="form-group">
                                            {{ Form::label('user_code', trans('webshoppack::admin/manageShops.shoplist_user_code'), array('class' => 'col-sm-4 control-label')) }}
                                            <div class="col-sm-8">
                                                {{ Form::text('user_code', Input::get("user_code"), array('class' => 'col-xs-10 col-sm-9', "placeholder"=> trans('webshoppack::admin/manageShops.shoplist_user_code_id'))) }}
                                            </div>
                                        </div>

										<div class="form-group">
                                            {{ Form::label('shop_featured', trans('webshoppack::admin/manageShops.shoplist_featured'), array('class' => 'col-sm-4 control-label')) }}
                                            <div class="col-sm-8">
                                                {{ Form::select('shop_featured', array('' => 'All', 'Yes' => 'Yes', 'No' => 'No'), Input::get("shop_featured"), array('class' => 'selectpicker')) }}
                                            </div>
                                        </div>
                                     </fieldset>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 pad-left4">
                                        <button type="submit" name="search_members" value="search_members" class="btn btn-sm btn-purple">
										{{ trans("webshoppack::common.search") }} <i class="icon-search bigger-110"></i></button>
                                        <button type="reset" name="reset_members" value="reset_members" class="btn btn-sm" onclick="javascript:location.href='{{ Request::url() }}'"><i class="icon-undo bigger-110"></i> {{ trans("webshoppack::common.reset")}}</button>
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
        	<h1 class="admin-title blue bigger-150">{{ trans('webshoppack::admin/manageShops.shoplist_page_title') }}</h1>
        </div><!--/.page-header-->
        {{ Form::open(array('id'=>'memberListfrm', 'method'=>'get','class' => 'form-horizontal form-request' )) }}
            <table class="table table-striped table-bordered table-hover">
                <thead class="thin-border-bottom">
                    <tr>
                    	<th>{{ trans('webshoppack::admin/manageShops.shoplist_shop_details') }} </th>
                        <th>{{ trans('webshoppack::admin/manageShops.shoplist_user_details') }} </th>
                        <th>{{ trans('webshoppack::admin/manageShops.shoplist_script_count') }} </th>
                        <th>{{ trans('webshoppack::admin/manageShops.shoplist_featured') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($shop_details) > 0)
                        @foreach($shop_details as $reqKey => $shop)
                            <tr>
                            	<td>
									<p><strong>{{ $shop['shop_name'] }} </strong></p>
									@if($shop->shop_city != '' && $shop->shop_state != '' && $shop->shop_country != '')
                                        <p>{{{ $shop->shop_city }}}, {{{ $shop->shop_state }}}, {{{ $country_arr[$shop->shop_country] }}}</p>
                                    @elseif($shop->shop_state != '' && $shop->shop_country != '')
                                        <p>{{{ $shop->shop_state }}}, {{{ $shop->shop_country }}}</p>
                                    @elseif($shop->shop_country != '')
                                        <p>{{{ $country_arr[$shop->shop_country] }}}</p>
                                    @endif
                                </td>
                                <td>
                                    <p><strong>{{ $shop['first_name'] }} {{ $shop['last_name'] }}</strong></p>
                                    <p><a href="mailto:{{ $shop['email'] }}">{{ $shop['email'] }}</a></p>
                                    <p title="User Code / User ID" class="grey">{{ Agriya\Webshoppack\CUtil::setUserCode($shop['user_id']) }} / {{ $shop['user_id'] }}</p>
                                    @if($shop['phone'] !="")
                                        <p title="User Phone" class="grey">{{trans('webshoppack::admin/manageShops.shoplist_phone_lbl')}}: {{ $shop['phone'] }}</p>
                                    @endif
                                </td>
                                <td>{{ $shop['script_cnt'] }}</td>
                                <td title="User Status">
                                	@if(strtolower($shop['is_featured_shop']) == "yes")
                                		<i class="icon-ok bigger-150 green" title='{{ ucwords(str_replace("_", " ", "Active")) }}'></i>
										<p>
											<a href="{{ URL::to(Config::get('webshoppack::admin_shop_uri').'/changestatus').'?action=removefeatured&shop_id='.$shop['id'] }}" class="fn_dialog_confirm red" action="Remove Featured" title="{{ trans('webshoppack::admin/manageShops.shoplist_remove_featured') }}">{{ trans('webshoppack::admin/manageShops.shoplist_remove_featured') }} </a>
										</p>
                                	@else
                                        <i class="icon-ban-circle bigger-150 red" title='{{ ucwords(str_replace("_", " ", "ToActivate")) }}'></i>
                                        <p>
											<a href="{{ URL::to(Config::get('webshoppack::admin_shop_uri').'/changestatus').'?action=setfeatured&shop_id='.$shop['id'] }}" class="fn_dialog_confirm green" action="Set Featured" title="{{ trans('webshoppack::admin/manageShops.shoplist_set_featured') }}">{{ trans('webshoppack::admin/manageShops.shoplist_set_featured') }} </a>
										</p>

                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                        	<td colspan="7">
                            	<p class="alert alert-info">{{ trans('webshoppack::admin/manageShops.shoplist_none_err_msg') }} </p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            @if(count($shop_details) > 0)
                <div class="text-center">{{ $shop_list->appends(array('user_code' => Input::get('user_code'), 'user_name' => Input::get('user_name'), 'user_email' => Input::get('user_email'), 'user_type' => Input::get('user_type'), 'status' => Input::get('status'), 'search_members' => Input::get('search_members')))->links() }}</div>
            @endif
        {{ Form::close() }}
		</div>
	</div>
	<div id="fn_dialog_confirm_msg" class="confirm-delete" style="display:none;"></div>
@stop
@section('script_content')
	<script type="text/javascript">
	$(".fn_viewgeo").fancybox({
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
	var common_ok_label = "{{ trans('webshoppack::common.yes') }}" ;
	var common_no_label = "{{ trans('webshoppack::common.cancel') }}" ;
	var cfg_package_name = "{{ Config::get('webshoppack::package_name') }}" ;
	$(window).load(function(){
		  $(".fn_dialog_confirm").click(function(){
				var atag_href = $(this).attr("href");
				var action = $(this).attr("action");
				var cmsg = "";
				var txtDelete = action;

				var txtCancel = common_no_label;
				var buttonText = {};
				buttonText[txtDelete] = function(){
											Redirect2URL(atag_href);
											$( this ).dialog( "close" );
										};
				buttonText[txtCancel] = function(){
											$(this).dialog('close');
										};
				switch(action){
					case "Set Featured":
						cmsg = "Are you sure you want to set this as featured shop?";

						break;
					case "Remove Featured":
						cmsg = "Are you sure you want to remove this from featured shop?";
						break;
				}
				$("#fn_dialog_confirm_msg").html(cmsg);
				$("#fn_dialog_confirm_msg").dialog({
					resizable: false,
					height:140,
					width: 320,
					modal: true,
					title: cfg_package_name,
					buttons:buttonText
				});
				return false;
			});
		});
	</script>
@stop