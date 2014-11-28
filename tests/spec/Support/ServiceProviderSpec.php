<?php

namespace spec\Hipchat\Support;

use GuzzleHttp\Client;
use Hipchat\Notifier;
use Hipchat\Room;
use Illuminate\Container\Container as Application;
use Illuminate\Config\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ServiceProviderSpec extends ObjectBehavior
{
    function let(Application $app)
    {
        $this->beConstructedWith($app);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Hipchat\Support\ServiceProvider');
    }

    function it_should_bind_hipchat(Application $app)
    {
        $app->bind('Hipchat\NotifierInterface', Argument::type('\Closure'))->shouldBeCalled();
        $app->alias('Hipchat\NotifierInterface', 'hipchat')->shouldBeCalled();
        $this->register();
    }

    function it_should_return_a_configured_notifier_object(
        Application $app,
        Repository $config,
        Client $client,
        Room $room,
        Notifier $notifier
    ) {
        // Setup
        $id = 1234;
        $token = '4cc3sst0k3n';
        $name = 'default';
        $rooms = [$name => ['room_id' => $id, 'auth_token' => $token]];
        $options = ['options' => 'data'];

        // Make predictions
        $app->make('config')->shouldBeCalledTimes(2)->willReturn($config);
        $config->get('hipchat::config.rooms')->shouldBeCalled()->willReturn($rooms);
        $config->get('hipchat::config')->shouldBeCalled()->willReturn($options);
        $app->make('GuzzleHttp\Client')->shouldBeCalled()->willReturn($client);
        $app->make('Hipchat\Room', [$id, $name, $token])->shouldBeCalled()->willReturn($room);
        $app->make('Hipchat\Notifier', [$client, $options])->shouldBeCalled()->willReturn($notifier);
        $notifier->addRoom($room)->shouldBeCalled()->willReturn($notifier);

        // Test hypotheses.
        $this->configureNotifier()->shouldReturn($notifier);
    }
}
