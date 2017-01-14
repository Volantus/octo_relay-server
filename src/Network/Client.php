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
     * @var int
     */
    private $id;

    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var bool
     */
    private $authenticated = false;

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
     * @param int $id
     * @param ConnectionInterface $connection
     * @param int $role
     */
    public function __construct(int $id, ConnectionInterface $connection, int $role)
    {
        $this->connection = $connection;
        $this->role = $role;
        $this->id = $id;
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

    public function setAuthenticated()
    {
        $this->authenticated = true;
    }

    /**
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}