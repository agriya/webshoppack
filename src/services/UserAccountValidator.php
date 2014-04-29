<?php namespace Agriya\Webshoppack;

class UserAccountValidator extends \Illuminate\Validation\Validator
{

	public function validateIsValidPrice($attribute, $value, $parameters)
	{
		if (preg_match("/^[0-9]+(\\.[0-9]{1,2})?$/", $value))
			{
				return true;
			}
		return false;
	}

	public function validateCustAfter($attribute, $value, $parameters)
	{
		return strtotime($parameters[1]) > strtotime($parameters[0]);
	}

	public function validateIsValidSlugUrl($attribute, $value, $parameters)
	{
		if (preg_match("/^[a-z0-9]([a-z0-9-])+?[a-z0-9]$/", $value))
		{
		      return true;
		}
		return false;
	}

	public function validateIsUserCodeExists($attribute, $value, $parameters)
	{
		$count = \DB::table('users_shop_details')->whereRaw("user_id = ? AND is_shop_owner = ?", array($parameters[0], $parameters[1]))->count();
		if($count)
			return true;
		return false;
	}
}