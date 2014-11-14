<?php
namespace Hipchat\Support;

use Hipchat\Notifier;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $config = $this->app['config'];
        $package = 'hannesvdvreken/hipchat';

        $config->package($package,
            base_path().'/vendor/'.$package.'/src/config'
        );

        $this->app->bind('hipchat', function ($app) use ($config) {
            return new Notifier(
                $app->make('Guzzle\Http\Client'),
                $config->get('hipchat::config.rooms'),
                $config->get('hipchat::config')
            );
        });
    }

    /**
     * Register an Alias for the Facade
     */
    public function boot()
    {
        // Add an alias for the Facade.
        $loader = AliasLoader::getInstance();
        $loader->alias('Hipchat', 'Hipchat\Support\Facades\Hipchat');
    }
}
