<?php
namespace Volante\SkyBukkit\RelayServer\Src\Messaging;

use Symfony\Component\Console\Output\OutputInterface;
use Volante\SkyBukkit\Common\Src\General\CLI\OutputOperations;
use Volante\SkyBukkit\Common\Src\General\GeoPosition\IncomingGeoPositionMessage;
use Volante\SkyBukkit\Common\Src\Server\Messaging\IncomingMessage;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageServerService;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageService;
use Volante\SkyBukkit\RelayServer\Src\GeoPosition\GeoPositionRepository;
use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Network\ClientFactory;
use Volante\SkyBukkit\RelayServer\Src\Subscription\RequestTopicStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\SubscriptionStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatusMessageFactory;

/**
 * Class MessageRelayService
 * @package Volante\SkyBukkit\RelayServer\Src\Messaging
 */
class MessageRelayService extends MessageServerService
{
    /**
     * @var GeoPositionRepository
     */
    private $geoPositionRepository;

    /**
     * @var TopicStatusMessageFactory
     */
    private $topicStatusMessageFactory;

    /**
     * MessageRelayService constructor.
     *
     * @param OutputInterface                     $output
     * @param IncomingMessageCreationService|null $messageService
     * @param ClientFactory|null                  $clientFactory
     * @param GeoPositionRepository               $geoPositionRepository
     * @param TopicStatusMessageFactory           $topicStatusMessageFactory
     */
    public function __construct(OutputInterface $output, IncomingMessageCreationService $messageService = null, ClientFactory $clientFactory = null, GeoPositionRepository $geoPositionRepository = null, TopicStatusMessageFactory $topicStatusMessageFactory = null)
    {
        parent::__construct($output, $messageService ?: new IncomingMessageCreationService(), $clientFactory ?: new ClientFactory());
        $this->geoPositionRepository = $geoPositionRepository ?: new GeoPositionRepository();
        $this->topicStatusMessageFactory = $topicStatusMessageFactory ?: new TopicStatusMessageFactory($this->geoPositionRepository);
    }

    /**
     * @param IncomingMessage $message
     */
    protected function handleMessage(IncomingMessage $message)
    {
        parent::handleMessage($message);

        switch (get_class($message)) {
            case IncomingGeoPositionMessage::class:
                /** @var IncomingGeoPositionMessage $message */
                $this->writeInfoLine('MessageRelayService', 'Received geo position message. Saving to repository ...');
                $this->writeInfoLine('MessageRelayService', json_encode($message->getGeoPosition()->toRawMessage()));
                $this->geoPositionRepository->add($message->getGeoPosition());
                break;
            case RequestTopicStatusMessage::class:
                /** @var RequestTopicStatusMessage $message */
                $this->writeInfoLine('MessageRelayService', 'Client ' . $message->getSender()->getId() . ' requested topic status, sending status ...');
                $message->getSender()->send(json_encode($this->topicStatusMessageFactory->getMessage()->toRawMessage()));
                break;
            case SubscriptionStatusMessage::class:
                /** @var SubscriptionStatusMessage $message */
                $this->writeInfoLine('MessageRelayService', 'Received subscription status from Client ' . $message->getSender()->getId());
                /** @var Client $sender */
                $sender = $message->getSender();
                $sender->setSubscriptions($message->getStatus());
                break;
        }
    }

}