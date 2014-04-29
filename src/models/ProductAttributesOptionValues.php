<?php namespace Agriya\Webshoppack;

class ProductAttributesOptionValues extends \Eloquent
{
    protected $table = "product_attributes_option_values";
    public $timestamps = false;
    protected $primarykey = '';
    protected $table_fields = array("product_id", "attribute_id", "attribute_options_id");
}