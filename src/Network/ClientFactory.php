<?php
namespace Volantus\RelayServer\Src\Network;

use Ratchet\ConnectionInterface;

/**
 * Class ClientFactory
 * @package Volantus\RelayServer\Src\Network
 */
class ClientFactory extends \Volantus\FlightBase\Src\Server\Network\ClientFactory
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