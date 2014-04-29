<?php namespace Agriya\Webshoppack;

class ProductImage extends \Eloquent
{
    protected $table = "product_image";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "product_id", "thumbnail_title", "thumbnail_img", "thumbnail_ext", "thumbnail_width", "thumbnail_height",
	"default_title", "default_img", "default_ext", "default_width", "default_height", "default_orig_img_width", "default_orig_img_height");
}