<?php
namespace Volante\SkyBukkit\RelayServer\Src\Network;

use Ratchet\ConnectionInterface;

/**
 * Class ClientFactory
 * @package Volante\SkyBukkit\RelayServer\Src\Network
 */
class ClientFactory
{
    /**
     * @var int
     */
    private $currentId = 0;

    /**
     * @param ConnectionInterface $connection
     * @return Client
     */
    public function get(ConnectionInterface $connection)
    {
        $this->currentId++;
        return new Client($this->currentId, $connection, -1);
    }
}