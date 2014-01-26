<?php
namespace Hipchat\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Hipchat extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hipchat';
    }
}
