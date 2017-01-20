<?php
namespace Volante\SkyBukkit\RelayServer\Src\Messaging;

use Symfony\Component\Console\Output\OutputInterface;
use Volante\SkyBukkit\Common\Src\General\CLI\OutputOperations;
use Volante\SkyBukkit\Common\Src\General\GeoPosition\IncomingGeoPositionMessage;
use Volante\SkyBukkit\Common\Src\Server\Messaging\IncomingMessage;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageServerService;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageService;
use Volante\SkyBukkit\RelayServer\Src\GeoPosition\GeoPositionRepository;
use Volante\SkyBukkit\RelayServer\Src\Network\ClientFactory;

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
     * MessageRelayService constructor.
     * @param OutputInterface $output
     * @param MessageService|null $messageService
     * @param ClientFactory|null $clientFactory
     * @param GeoPositionRepository $geoPositionRepository
     */
    public function __construct(OutputInterface $output, MessageService $messageService = null, ClientFactory $clientFactory = null, GeoPositionRepository $geoPositionRepository = null)
    {
        parent::__construct($output, $messageService, $clientFactory ?: new ClientFactory());
        $this->geoPositionRepository = $geoPositionRepository ?: new GeoPositionRepository();
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
        }
    }

}