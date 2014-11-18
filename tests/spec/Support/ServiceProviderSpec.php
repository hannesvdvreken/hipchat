<?php

namespace spec\Hipchat\Support;

use Guzzle\Http\Client;
use Hipchat\Notifier;
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
        Notifier $notifier
    ) {
        // Setup
        $rooms = ['default' => 'configuration data'];
        $options = ['options' => 'data'];

        // Make predictions
        $app->make('config')->shouldBeCalledTimes(2)->willReturn($config);
        $config->get('hipchat::config.rooms')->shouldBeCalled()->willReturn($rooms);
        $config->get('hipchat::config')->shouldBeCalled()->willReturn($options);
        $app->make('Guzzle\Http\Client')->shouldBeCalled()->willReturn($client);
        $app->make('Hipchat\Notifier', [$client, $rooms, $options])->shouldBeCalled()->willReturn($notifier);

        // Test hypotheses.
        $this->configureNotifier()->shouldReturn($notifier);
    }
}
