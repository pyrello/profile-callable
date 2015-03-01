<?php namespace Pyrello\ProfileCallable;

use Illuminate\Support\ServiceProvider;

class ProfileCallableServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app['profile.callable'] = $this->app->share(function($app)
        {
            return new ProfileCallableCommand();
        });

        $this->commands('profile.callable');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
