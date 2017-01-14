<?php
namespace Volante\SkyBukkit\RelayServer\Src;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Volante\SkyBukkit\RelayServer\Src\Messaging\MessageRelayService;

/**
 * Class OperatorServer
 * @package Volante\SkyBukkit\Monitor\App
 */
class Controller implements MessageComponentInterface
{
    /**
     * @var MessageRelayService
     */
    private $service;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * Controller constructor.
     * @param OutputInterface $output
     * @param MessageRelayService $messageRelayService
     */
    public function __construct(OutputInterface $output, MessageRelayService $messageRelayService)
    {
        $this->service = $messageRelayService;
        $this->output = $output;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->service->newClient($conn);
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $this->service->newMessage($from, $msg);
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->service->removeClient($conn);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->output->writeln('<error>[Controller] ' . $e->getMessage() . '</error>');
        $this->service->removeClient($conn);
    }
}