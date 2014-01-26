<?php
namespace Hipchat;

interface NotifierInterface
{
    public function notify($message);
    public function notifyIn($room, $message);
}
