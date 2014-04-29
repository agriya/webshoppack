<?php namespace Agriya\Webshoppack;

class UsersShopDetails extends \Eloquent
{
    protected $table = "users_shop_details";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "user_id", "is_shop_owner", "shop_status", "total_products", "paypal_id");
}