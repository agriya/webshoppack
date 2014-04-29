<?php namespace Agriya\Webshoppack;

class CurrencyExchangeRate extends \Eloquent
{
    protected $table = "currency_exchange_rate";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "country", "country_code", "currency_code", "currency_symbol", "currency_name", "exchange_rate", "status", "paypal_supported", "display_currency");
}