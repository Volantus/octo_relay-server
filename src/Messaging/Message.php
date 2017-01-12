<?php
namespace Volante\SkyBukkit\RelayServer\Src\Messaging;

use Volante\SkyBukkit\RelayServer\Src\Network\Client;

/**
 * Class Message
 * @package Volante\SkyBukkit\RelayServer\Src\FlightStatus\Network
 */
abstract class Message
{
    /**
     * @var Client
     */
    private $sender;

    /**
     * Message constructor.
     * @param Client $sender
     */
    public function __construct(Client $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return Client
     */
    public function getSender(): Client
    {
        return $this->sender;
    }
}