<?php

//Filters

Route::filter('validate_login', function ()
{
	//$configFactory = App::make('admin_config_factory');
	//get the admin check closure that should be supplied in the config
	$permission = Config::get('webshoppack::permission');
	$response = $permission();

	//if this is a simple false value, send the user to the login redirect
	if (!$response)
	{
		$loginUrl = URL::to(Config::get('webshoppack::login_path', 'webshop'));
		$redirectKey = Config::get('webshoppack::login_redirect_key', 'redirect');
		$redirectUri = Request::url();

		return Redirect::guest($loginUrl)->with($redirectKey, $redirectUri);
	}
	//otherwise if this is a response, return that
	else if (is_a($response, 'Illuminate\Http\JsonResponse') || is_a($response, 'Illuminate\Http\Response'))
	{
		return $response;
	}
	//if it's a redirect, send it back with the redirect uri
	else if (is_a($response, 'Illuminate\\Http\\RedirectResponse'))
	{
		return $response->with($redirectKey, $redirectUri);
	}
});

Route::filter('validate_admin', function()
{
	if (!Sentry::check()) return Redirect::to(Config::get('webshopauthenticate::uri').'/login');
	if (Sentry::getUser()->hasAnyAccess(['system', 'system.Admin']))
	return Redirect::to(Config::get('webshopauthenticate::uri').'/login');
});