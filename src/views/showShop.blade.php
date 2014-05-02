<div class="panel panel-info no-mar">
    <div class="panel-heading">
        @if(count($d_arr['shop_product_list']) > 0)
            <a href='{{ $d_arr['shop_url'] }}' class="pull-right"><i class="fa fa-angle-double-right"></i> see more</a>
        @endif
        <h4>Shop Products</h4>
    </div>
    @if(count($d_arr['shop_product_list']) > 0)
        <ul class="list-unstyled list-inline userprofile-shop clearfix mb0">
            @foreach($d_arr['shop_product_list'] AS $prd)
                <?php
                    $p_img_arr = Webshoppack::populateProductDefaultThumbImages($prd->id);
                    $p_thumb_img = Webshoppack::getProductDefaultThumbImage($prd->id, 'thumb', $p_img_arr);
                    $view_url = Webshoppack::getProductViewURL($prd->id, $prd);
                ?>
                <li>
                    <a href="{{ $view_url }}" class="img81x64"><img src="{{ $p_thumb_img['image_url'] }}" @if(isset($p_thumb_img["thumbnail_width"])) width='{{ $p_thumb_img["thumbnail_width"] }}' height='{{ $p_thumb_img["thumbnail_height"] }}' @endif title="{{ $prd->product_name  }}" alt="{{ $prd->product_name  }}" /></a>
                </li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-info">No Products Found</div>
    @endif
</div>