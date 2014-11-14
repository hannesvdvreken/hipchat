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
        $this->app->bind('Hipchat\NotifierInterface', function ($app) {
            $client = $app->make('Guzzle\Http\Client');
            $rooms = $app['config']->get('hipchat::config.rooms');
            $options = $app['config']->get('hipchat::config');

            return $app->make('Hipchat\Notifier', [$client, $rooms, $options]);
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
}
