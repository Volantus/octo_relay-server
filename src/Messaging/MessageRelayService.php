<?php
namespace Volante\SkyBukkit\RelayServer\Src\Messaging;

use Ratchet\ConnectionInterface;
use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Network\ClientFactory;
use Volante\SkyBukkit\RelayServer\Src\Role\IntroductionMessage;

/**
 * Class MessageRelayService
 * @package Volante\SkyBukkit\Monitor\Src\FlightStatus\Network
 */
class MessageRelayService
{
    /**
     * @var MessageService
     */
    private $messageService;

    /**
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * @var Client[]
     */
    private $clients = [];

    /**
     * MessageRelayService constructor.
     * @param MessageService $messageService
     * @param ClientFactory $clientFactory
     */
    public function __construct(MessageService $messageService = null, ClientFactory $clientFactory = null)
    {
        $this->messageService = $messageService ?: new MessageService();
        $this->clientFactory = $clientFactory ?: new ClientFactory();
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function newClient(ConnectionInterface $connection)
    {
        $this->clients[] = $this->clientFactory->get($connection);
    }

    /**
     * @param ConnectionInterface $connection
     * @param string $message
     */
    public function newMessage(ConnectionInterface $connection, string $message)
    {
        $client = $this->findClient($connection);
        $message = $this->messageService->handle($client, $message);

        switch (get_class($message)) {
            case IntroductionMessage::class:
                /** @var IntroductionMessage $message */
                $client->setRole($message->getRole());
                break;
        }
    }

    /**
     * @param ConnectionInterface $connection
     * @return Client
     */
    private function findClient(ConnectionInterface $connection) : Client
    {
        foreach ($this->clients as $client) {
            if ($client->getConnection() === $connection) {
                return $client;
            }
        }

        throw new \RuntimeException('No connected client found!');
    }
}