<?php namespace Agriya\Webshoppack;

class ProductAttributeOptions extends \Eloquent
{
    protected $table = "product_attribute_options";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "attribute_id", "option_label", "option_value", "is_default_option");
}