<?php
namespace Hipchat;

use Guzzle\Http\Client;

class Notifier implements NotifierInterface
{
    /**
     * @var  string
     */
    protected $default;

    /**
     * @var  string
     */
    protected $notify;

    /**
     * @var  string
     */
    protected $color;

    /**
     * @var  boolean
     */
    protected $pretend;

    /**
     * @var  array
     */
    protected $rooms;

    /**
     * @var  array
     */
    protected $colors = array('yellow', 'red', 'green', 'purple', 'gray', 'random');

    /**
     * @var  Guzzle\Http\Client
     */
    protected $client;

    /**
     * Public constructor
     *
     * @param  Guzzle\Http\Client $client
     * @param  array  $rooms
     * @param  array  $config
     * @return Hipchat\NotifierInterface
     */
    public function __construct(Client $client, $rooms, $config = array())
    {
        // Configure the HTTP client
        $this->client = $client->setBaseUrl('https://api.hipchat.com/')
            ->setDefaultOption('headers', array('Content-Type' => 'application/json'));

        // Set the rooms array.
        $this->rooms = $rooms;

        // Get room keys.
        $roomKeys = array_keys($rooms);

        // Extract the configuration array.
        $this->default = isset($config['default']) ? $config['default'] : $roomKeys[0];
        $this->notify  = isset($config['notify'])  ? $config['notify']  : true;
        $this->color   = isset($config['color'])   ? $config['color']   : 'gray';
        $this->pretend = isset($config['pretend']) ? $config['pretend'] : false;
    }

    /**
     * Notify with specified $message.
     * 
     * @param  string $message
     * @param  string $color
     * @return Hipchat\NotifierInterface
     */
    public function notify($message, $color = null)
    {
        return $this->notifyIn($this->default, $message, $color);
    }

    /**
     * Notify with a specified $message in a given $room.
     * 
     * @param  string $room
     * @param  string $message
     * @param  string $color
     * @return NotifierInterface
     */
    public function notifyIn($room, $message, $color = null)
    {
        // Don't do any request.
        if ($this->pretend) return $this;

        // Construct POST data.
        $data = array(
            'color' => in_array($color, $this->colors) ? $color : $this->color,
            'message' => $message,
            'notify' => $this->notify,
            'message_format' => 'html',
        );

        // Get the room settings
        $room = $this->rooms[$room];

        // Set authentication
        $this->client->setDefaultOption('query', array('auth_token' => $room['auth_token']));

        // Make request.
        $this->client->post('/v2/room/'. $room['room_id'] .'/notification', null, json_encode($data))->send();

        // Allow chaining.
        return $this;
    }
}
