<?php
namespace Hipchat\Support;

use Hipchat\Notifier;
use Hipchat\Room;
use Illuminate\Config\Repository;
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
        $this->config()->package('hannesvdvreken/hipchat', __DIR__.'/../config');
    }

    /**
     * @return Notifier
     */
    public function configureNotifier()
    {
        // Create HTTP Client.
        $client = $this->app->make('GuzzleHttp\Client');

        // Get some configuration data.
        $rooms = $this->config()->get('hipchat::config.rooms');
        $options = $this->config()->get('hipchat::config');

        // Instantiate the Notifier object and return it.
        $notifier = $this->app->make('Hipchat\Notifier', [$client, $options]);

        // Configure all rooms.
        foreach ($rooms as $name => $room) {
            $room = $this->app->make('Hipchat\Room', [$room['room_id'], $name, $room['auth_token']]);
            $notifier->addRoom($room);
        }

        // Return the configured notifier.
        return $notifier;
    }

    /**
     * @return Repository
     */
    protected function config()
    {
        return $this->app->make('config');
    }
}
