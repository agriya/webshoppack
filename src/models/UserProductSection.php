<?php namespace Agriya\Webshoppack;

class UserProductSection extends \Eloquent
{
    protected $table = "user_product_section";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "user_id", "section_name", "date_added", "status");
}