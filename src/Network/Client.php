<?php
namespace Volante\SkyBukkit\RelayServer\Src\Network;

use Ratchet\ConnectionInterface;
use Volante\SkyBukkit\RelayServer\Src\Subscription\Topic;


/**
 * Class Connection
 * @package Volante\SkyBukkit\Monitor\Src\FlightStatus\Network
 */
class Client
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var Topic[]
     */
    private $subscriptions = [];

    /**
     * @var string
     */
    private $role;

    /**
     * Client constructor.
     * @param ConnectionInterface $connection
     * @param string $role
     */
    public function __construct(ConnectionInterface $connection, string $role)
    {
        $this->connection = $connection;
        $this->role = $role;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    /**
     * @return Topic[]
     */
    public function getSubscriptions(): array
    {
        return $this->subscriptions;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }
}