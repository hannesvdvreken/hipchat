<?php

namespace spec\Hipchat;

use PhpSpec\ObjectBehavior;

class RoomSpec extends ObjectBehavior
{
    function it_should_have_setters()
    {
        // Variables.
        $id = '1234';
        $name = 'default';
        $token = '4cc3sst0k3n';

        // Test hypotheses.
        $this->beConstructedWith($id, $name, $token);
        $this->id()->shouldReturn($id);
        $this->name()->shouldReturn($name);
        $this->token()->shouldReturn($token);
    }
}
