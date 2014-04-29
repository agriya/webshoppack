<?php namespace Agriya\Webshoppack;

class ProductAttributesValues extends \Eloquent
{
    protected $table = "product_attributes_values";
    public $timestamps = false;
    protected $primarykey = '';
    protected $table_fields = array("product_id", "attribute_id", "attribute_value");
}