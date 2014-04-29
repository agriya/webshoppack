@if(!$ajax_page)
	<div class="mt30">
		<div id="category_details"></div>
		<ul class="nav nav-tabs padding-12 tab-color-blue background-blue">
			<li class="active"><a href="#category_info_block" data-toggle="tab">{{ trans('webshoppack::admin/manageCategory.category_info_title') }}</a></li>
			<li><a href="#attributes_block" data-toggle="tab">{{ trans('webshoppack::admin/manageCategory.attributes_title') }}</a></li>
		</ul>
		<div class="tab-content">
			<div id="category_info_block" class="tab-pane active"></div>
			<div id="attributes_block" class="tab-pane"></div>
		</div>
	</div>
@else
	<div class="clsBreadCrumb page-header mb20">
	    @if($category_id == $root_category_id)
	        <h1>{{ trans('webshoppack::admin/manageCategory.new_category_title') }}</h1>
	    @elseif($display_block == 'add_sub_category')
	    	<h1>{{ trans('webshoppack::admin/manageCategory.sub_category_title') }} {{ trans('webshoppack::common.for') }} {{ $category_details['full_parent_category_name']}}</h1>
	    @else
	        <a href="javascript:void(0);" onclick="addSubCategory({{$category_details['id']}});"; title="{{ trans('webshoppack::admin/manageCategory.sub_category_title') }}" class="btn btn-info btn-xs pull-right"><i class="icon-plus-sign"></i> {{ trans('webshoppack::admin/manageCategory.sub_category_title') }}</a>
			<h1>{{ $category_details['category_name'] }}</h1>
	    @endif
	</div>
@endif