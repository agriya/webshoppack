<?php namespace Agriya\Webshoppack;

use Illuminate\Support\ServiceProvider;

class WebshoppackServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('agriya/webshoppack');
		$this->app->register('Intervention\Image\ImageServiceProvider');
		$this->app['validator']->resolver(function($translator, $data, $rules, $messages)
	    {
	        return new UserAccountValidator($translator, $data, $rules, $messages);
	    });
		include __DIR__.'/../../filters.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['webshoppack'] = $this->app->share(function($app)
		{
			return new Webshoppack;
		});
		$this->app->booting(function()
		{
		  	$loader = \Illuminate\Foundation\AliasLoader::getInstance();
		  	$loader->alias('Webshoppack', 'Agriya\Webshoppack\Facades\Webshoppack');
		  	$loader->alias('Image', 'Intervention\Image\Facades\Image');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('webshoppack');
	}

}
