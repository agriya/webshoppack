<?php namespace Agriya\Webshoppack;

class ProductCategoryAttributes extends \Eloquent
{
    protected $table = "product_category_attributes";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "attribute_id", "category_id", "date_added", "display_order");
}