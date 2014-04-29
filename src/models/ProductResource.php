<?php namespace Agriya\Webshoppack;

class ProductResource extends \Eloquent
{
    protected $table = "product_resource";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "product_id", "resource_type", "is_downloadable", "filename", "ext", "title", "default_flag", "server_url", "display_order",
									"width", "height", "l_width", "l_height", "t_width", "t_height");
}