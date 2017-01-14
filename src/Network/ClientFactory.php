<?php
namespace Volante\SkyBukkit\RelayServer\Src\Network;

use Ratchet\ConnectionInterface;

/**
 * Class ClientFactory
 * @package Volante\SkyBukkit\RelayServer\Src\Network
 */
class ClientFactory extends \Volante\SkyBukkit\Common\Src\Server\Network\ClientFactory
{
    /**
     * @param ConnectionInterface $connection
     * @return Client
     */
    public function get(ConnectionInterface $connection)
    {
        return new Client($this->getNextId(), $connection, -1);
    }
}