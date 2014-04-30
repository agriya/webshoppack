<?php namespace Agriya\Webshoppack;
class CustomEloquent extends \Eloquent {
	protected $table_fields = array();
	public function setFieldValue($key, $value)
    {
    	if(in_array($key, $this->table_fields)) $this->$key = $value;
	}
	public function setFieldValues($arr = array())
    {
    	foreach($arr as $key => $value)
    	{
    		if(in_array($key, $this->table_fields)) $this->$key = $value;
    	}
	}
	public function filterTableFields($in_arr = array())
    {
    	foreach($in_arr as $key => $value)
    	{
    		if(in_array($key, $this->table_fields)){ $arr[$key] = $value; }
    	}
    	return $arr;
	}


}