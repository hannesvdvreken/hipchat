<?php
namespace Hipchat;

class Room
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $token;

    /**
     * @param int    $id
     * @param string $name
     * @param string $token
     */
    public function __construct($id, $name, $token)
    {
        $this->id    = $id;
        $this->name  = $name;
        $this->token = $token;
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function token()
    {
        return $this->token;
    }
}
