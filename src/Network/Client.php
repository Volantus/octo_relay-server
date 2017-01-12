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
     * @var int
     */
    private $role;

    /**
     * Client constructor.
     * @param ConnectionInterface $connection
     * @param int $role
     */
    public function __construct(ConnectionInterface $connection, int $role)
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
     * @param int $role
     */
    public function setRole(int $role)
    {
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }
}