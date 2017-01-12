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
     * @param ConnectionInterface $connection
     * @return Client
     */
    public function get(ConnectionInterface $connection)
    {
        return new Client($connection, -1);
    }
}