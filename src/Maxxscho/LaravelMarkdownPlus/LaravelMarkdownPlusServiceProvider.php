<?php namespace Maxxscho\LaravelMarkdownPlus;

use Illuminate\Support\ServiceProvider;

class LaravelMarkdownPlusServiceProvider extends ServiceProvider {

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
		$this->package('maxxscho/laravel-markdown-plus');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['markdownplus'] = $this->app->share(function ($app)
		{
			return new MarkdownPlus;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('markdownplus');
	}

}
