<?php namespace Agriya\Webshoppack;

class ApiExcludeTags extends \Eloquent
{
    protected $table = "api_exclude_tags";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "tags");
}