<?php namespace Agriya\Webshoppack;

class ProductCategory extends \Eloquent
{
    protected $table = "product_category";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "seo_category_name", "category_name", "category_description", "category_meta_title", "category_meta_description",	"category_meta_keyword", "category_level", "parent_category_id", "category_left", "category_right", "date_added", "display_order", "available_sort_options", "image_name", "image_ext", "image_width", "image_height", "is_featured_category", "use_parent_meta_detail", "status");
}