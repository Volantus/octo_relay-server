<?php
namespace Volante\SkyBukkit\RelayServer\Src;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/**
 * Class OperatorServer
 * @package Volante\SkyBukkit\Monitor\App
 */
class Controller implements MessageComponentInterface
{
    /**
     * @var \SplObjectStorage
     */
    protected $clients;

    /**
     * @var array
     */
    protected $data = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg) {
        $this->data[] = $msg;

        foreach ($this->clients as $client) {
            $client->send(json_encode($this->data, JSON_PRETTY_PRINT));
        }
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}