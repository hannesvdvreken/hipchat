<?php
namespace Hipchat;

use GuzzleHttp\Client;
use InvalidArgumentException;
use SebastianBergmann\Exporter\Exception;
use SplObjectStorage;

class Notifier implements NotifierInterface
{
    /**
     * @var string
     */
    protected $notify;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var boolean
     */
    protected $pretend;

    /**
     * @var SplObjectStorage
     */
    protected $rooms;

    /**
     * @var array
     */
    protected $colors = ['yellow', 'red', 'green', 'purple', 'gray', 'random'];

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    const GRAY = 'gray';

    /**
     * @var string
     */
    private $baseUrl = 'https://api.hipchat.com';

    /**
     * Public constructor
     *
     * @param Client     $client
     * @param array      $config
     * @param array|Room $rooms
     */
    public function __construct(Client $client, array $config = [], $rooms = [])
    {
        // Configure the HTTP client
        $this->setClient($client);
        $this->setDefaultOptions($config);

        // Set the rooms array.
        $this->rooms = new SplObjectStorage();

        foreach (is_array($rooms) ? $rooms : [$rooms] as $room) {
            $this->addRoom($room);
        }
    }

    /**
     * Notify with specified $message.
     *
     * @param  string $message
     * @param  array  $options
     *
     * @return NotifierInterface
     */
    public function notify($message, array $options = [])
    {
        return $this->notifyIn($this->defaultRoom(), $message, $options);
    }

    /**
     * Notify with a specified $message in a given $room.
     *
     * @param  string $room
     * @param  string $message
     * @param  array  $options
     *
     * @return NotifierInterface
     */
    public function notifyIn($room, $message, array $options = [])
    {
        // Don't do any request.
        if ($this->getOption('pretend', $options)) {
            return $this;
        }

        // Construct POST data.
        $json = [
            'message'        => $message,
            'message_format' => 'html',
            'color'          => $this->getOption('color', $options),
            'notify'         => $this->getOption('notify', $options),
        ];

        // Get the room settings
        $room = $this->getRoom($room);

        // Set authentication
        $query = ['auth_token' => $room->token()];

        // Build URI.
        $uri = $this->getRoomNotificationUrl($room);

        // Make request.
        $this->client->post($uri, compact('json', 'query'));

        // Allow chaining.
        return $this;
    }

    /**
     * @param Client $client
     *
     * @return $this
     */
    public function setClient(Client $client)
    {
        // Assign the client.
        $this->client = $client;
        $this->client->setDefaultOption('headers', ['Content-Type' => 'application/json']);

        // Allow chaining.
        return $this;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Room $room
     *
     * @return $this
     */
    public function addRoom(Room $room)
    {
        $this->rooms->attach($room);

        // Allow chaining
        return $this;
    }

    /**
     * @param Room $room
     *
     * @return $this
     */
    public function deleteRoom(Room $room)
    {
        $this->rooms->detach($room);

        // Allow chaining
        return $this;
    }

    /**
     * @param Room $room
     *
     * @return string
     */
    protected function getRoomNotificationUrl(Room $room)
    {
        return "{$this->baseUrl}/v2/room/{$room->id()}/notification";
    }

    /**
     * @param array $config
     */
    protected function setDefaultOptions(array $config)
    {
        $this->notify  = isset($config['notify']) ? $config['notify'] : true;
        $this->color   = isset($config['color']) ? $config['color'] : self::GRAY;
        $this->pretend = isset($config['pretend']) ? $config['pretend'] : false;
    }

    /**
     * @param string $option
     * @param array  $options
     *
     * @return string
     */
    private function getOption($option, array $options)
    {
        return isset($options[$option]) ? $options[$option] : $this->$option;
    }

    /**
     * @param Room|string $room
     *
     * @return Room
     */
    private function getRoom($room)
    {
        // This is great!
        if ($room instanceof Room && $this->rooms->contains($room)) {
            return $room;
        }

        /** @var Room $item */
        foreach ($this->rooms as &$item) {
            if ($item->name() === $room) {
                $room = $item;
                break;
            }
        }

        // Reset the iterator first.
        $this->rooms->rewind();
        return $room;

        // This was not part of the deal.
        throw new InvalidArgumentException("$room is not a valid argument. No such room found.");
    }

    /**
     * @return Room
     */
    private function defaultRoom()
    {
        return $this->rooms->current();
    }
}
