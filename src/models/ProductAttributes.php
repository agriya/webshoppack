<?php namespace Agriya\Webshoppack;

class ProductAttributes extends \Eloquent
{
    protected $table = "product_attributes";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "attribute_label", "attribute_help_tip", "attribute_question_type", "default_value", "validation_rules", "date_added", "is_searchable", "show_in_list", "description", "status");
}