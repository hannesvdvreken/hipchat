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
        // Bind implementation for interface.
        $this->app->bind('Hipchat\NotifierInterface', function () {
            return $this->configureNotifier();
        });

        // Set alias.
        $this->app->alias('Hipchat\NotifierInterface', 'hipchat');
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

    /**
     * @return Notifier
     */
    public function configureNotifier()
    {
        $client = $this->app->make('Guzzle\Http\Client');
        $rooms = $this->app['config']->get('hipchat::config.rooms');
        $options = $this->app['config']->get('hipchat::config');

        return $this->app->make('Hipchat\Notifier', [$client, $rooms, $options]);
    }
}
