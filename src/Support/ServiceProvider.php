<?php
namespace Hipchat\Support;

use Hipchat\Notifier;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Foundation\AliasLoader;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return  void
     */
    public function register()
    {
        $config = $this->app['config'];
        $package = 'hannesvdvreken/hipchat';

        $config->package($package,
            base_path() . '/vendor/'. $package .'/src/config'
        );

        $this->app->bind('hipchat', function($app) use ($config)
        {
            return new Notifier(
                $app->make('Guzzle\Http\Client'),
                $config->get('hipchat::config.rooms'),
                $config->get('hipchat::config')
            );
        });
    }

    /**
     * Register an Alias for the Facade
     *
     * @return void
     */
    public function boot()
    {
        // Add an alias for the Facade.
        $loader = AliasLoader::getInstance();
        $loader->alias('Hipchat', 'Hipchat\Support\Facades\Hipchat');
    }
}
