<?php namespace Agriya\Webshoppack;

class ProductLog extends \Eloquent
{
    protected $table = "product_log";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "product_id", "date_added", "added_by", "user_id", "notes");
}