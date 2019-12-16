<?php namespace Maxxscho\LaravelMarkdownPlus;

use Illuminate\Support\ServiceProvider;

class LaravelMarkdownPlusServiceProvider extends ServiceProvider
{

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
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('laravel-markdown-plus.php'),
        ]);
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
            return new MarkdownPlus($app['config']);
        });

        $this->mergeConfigFrom(
            __DIR__.'/../../config/config.php', 'laravel-markdown-plus'
        );
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
