<?php

namespace spec\Hipchat;

use GuzzleHttp\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NotifierSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $rooms = ['default' => []];
        $this->beConstructedWith($client, $rooms);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Hipchat\Notifier');
    }
}
