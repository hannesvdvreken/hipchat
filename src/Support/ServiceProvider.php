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
        $this->app->bind('hipchat', function ($app) {
            return new Notifier(
                $app->make('Guzzle\Http\Client'),
                $app['config']->get('hipchat::config.rooms'),
                $app['config']->get('hipchat::config')
            );
        });
    }

    /**
     * Register an Alias for the Facade
     */
    public function boot()
    {
        // Register config folder.
        $this->app['config']->package('hannesvdvreken/hipchat', __DIR__.'/../config');

        // Add an alias for the Facade.
        AliasLoader::getInstance(['Hipchat' => 'Hipchat\Support\Facades\Hipchat'])->register();
    }
}
