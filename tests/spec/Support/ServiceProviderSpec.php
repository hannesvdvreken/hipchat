<?php

namespace spec\Hipchat\Support;

use Illuminate\Container\Container as Application;
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
}
