<?php
namespace Volante\SkyBukkit\RelayServer\Src\Messaging;

use Ratchet\ConnectionInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Volante\SkyBukkit\Common\Src\CLI\OutputOperations;
use Volante\SkyBukkit\Common\Src\Role\ClientRole;
use Volante\SkyBukkit\RelayServer\Src\Authentication\AuthenticationMessage;
use Volante\SkyBukkit\RelayServer\Src\Authentication\UnauthorizedException;
use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Network\ClientFactory;
use Volante\SkyBukkit\RelayServer\Src\Role\IntroductionMessage;

/**
 * Class MessageRelayService
 * @package Volante\SkyBukkit\Monitor\Src\FlightStatus\Network
 */
class MessageRelayService
{
    use OutputOperations;

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
     * @param OutputInterface $output
     * @param MessageService $messageService
     * @param ClientFactory $clientFactory
     */
    public function __construct(OutputInterface $output, MessageService $messageService = null, ClientFactory $clientFactory = null)
    {
        $this->output = $output;
        $this->messageService = $messageService ?: new MessageService();
        $this->clientFactory = $clientFactory ?: new ClientFactory();
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function newClient(ConnectionInterface $connection)
    {
        $this->sandbox(function () use ($connection) {
            $this->clients[] = $client = $this->clientFactory->get($connection);
            $this->writeInfoLine('MessageRelayService', 'New client ' . $client->getId() . ' connected!');
        });
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function removeClient(ConnectionInterface $connection)
    {
        $this->sandbox(function () use ($connection) {
            $client = $this->findClient($connection);
            $this->disconnectClient($client);
        });
    }

    /**
     * @param ConnectionInterface $connection
     * @param string $message
     */
    public function newMessage(ConnectionInterface $connection, string $message)
    {
        $this->sandbox(function () use ($connection, $message) {
            $client = $this->findClient($connection);
            $message = $this->messageService->handle($client, $message);

            switch (get_class($message)) {
                case AuthenticationMessage::class:
                    /** @var AuthenticationMessage $message */
                    $this->handleAuthenticationMessage($message);
                    break;
                case IntroductionMessage::class:
                    /** @var IntroductionMessage $message */
                    $this->handleIntroductionMessage($message);
                    break;
            }
        });
    }

    /**
     * @param callable $function
     */
    protected function sandbox(callable $function)
    {
        try {
            call_user_func($function);
        } catch (\Exception $e) {
            $this->writeErrorLine('MessageRelayService', $e->getMessage());
        } catch (\TypeError $e) {
            $this->writeErrorLine('MessageRelayService', $e->getMessage());
        }
    }

    /**
     * @param AuthenticationMessage $message
     */
    protected function handleAuthenticationMessage(AuthenticationMessage $message)
    {
        if ($message->getToken() === getenv('AUTH_TOKEN')) {
            $message->getSender()->setAuthenticated();
            $this->writeInfoLine('MessageRelayService', 'Client ' . $message->getSender()->getId() . ' authenticated successfully.');
        } else {
            $this->disconnectClient($message->getSender());
            throw new UnauthorizedException('Client ' . $message->getSender()->getId() . ' tried to authenticate with wrong token!');
        }
    }

    /**
     * @param IntroductionMessage $message
     */
    protected function handleIntroductionMessage(IntroductionMessage $message)
    {
        $this->authenticate($message->getSender());
        $message->getSender()->setRole($message->getRole());
        $this->writeInfoLine('MessageRelayService', 'Client ' . $message->getSender()->getId() . ' introduced as ' . ClientRole::getTitle($message->getRole()) . '.');
    }

    /**
     * @param Client $client
     */
    private function authenticate(Client $client)
    {
        if (!$client->isAuthenticated()) {
            $this->disconnectClient($client);
            throw new UnauthorizedException('Client ' . $client->getId() . ' tried to perform unauthenticated action!');
        }
    }

    /**
     * @param Client $removedClient
     */
    private function disconnectClient(Client $removedClient)
    {
        $removedClient->getConnection()->close();
        foreach ($this->clients as $i => $client) {
            if ($client === $removedClient) {
                unset($this->clients[$i]);
                $this->clients = array_values($this->clients);
                break;
            }
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