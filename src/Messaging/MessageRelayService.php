<?php
namespace Volante\SkyBukkit\RelayServer\Src\Messaging;

use Symfony\Component\Console\Output\OutputInterface;
use Volante\SkyBukkit\Common\Src\General\FlightController\IncomingPIDFrequencyStatus;
use Volante\SkyBukkit\Common\Src\General\FlightController\IncomingPIDTuningStatusMessage;
use Volante\SkyBukkit\Common\Src\General\GeoPosition\IncomingGeoPositionMessage;
use Volante\SkyBukkit\Common\Src\General\GyroStatus\IncomingGyroStatusMessage;
use Volante\SkyBukkit\Common\Src\General\Motor\IncomingMotorControlMessage;
use Volante\SkyBukkit\Common\Src\General\Motor\IncomingMotorStatusMessage;
use Volante\SkyBukkit\Common\Src\General\Role\ClientRole;
use Volante\SkyBukkit\Common\Src\Server\Messaging\IncomingMessage;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageServerService;
use Volante\SkyBukkit\RelayServer\Src\FlightController\PidFrequencyStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\FlightController\PidTuningStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\GeoPosition\GeoPositionRepository;
use Volante\SkyBukkit\RelayServer\Src\GyroStatus\GyroStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\Motor\MotorStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Network\ClientFactory;
use Volante\SkyBukkit\RelayServer\Src\Subscription\RequestTopicStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\SubscriptionStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicRepository;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatus;
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
     * @var GyroStatusRepository
     */
    private $gyroStatusRepository;

    /**
     * @var MotorStatusRepository
     */
    private $motorStatusRepository;

    /**
     * @var PidFrequencyStatusRepository
     */
    private $pidFrequencyStatusRepository;

    /**
     * @var PidTuningStatusRepository
     */
    private $pidTuningStatusRepository;

    /**
     * @var TopicStatusMessageFactory
     */
    private $topicStatusMessageFactory;

    /**
     * @var TopicRepository[]
     */
    private $repositories = [];

    /**
     * @var Client[]
     */
    protected $clients = [];

    /**
     * MessageRelayService constructor.
     *
     * @param OutputInterface                     $output
     * @param IncomingMessageCreationService|null $messageService
     * @param ClientFactory|null                  $clientFactory
     * @param GeoPositionRepository               $geoPositionRepository
     * @param GyroStatusRepository                $gyroStatusRepository
     * @param MotorStatusRepository               $motorStatusRepository
     * @param PidFrequencyStatusRepository        $pidFrequencyStatusRepository
     * @param PidTuningStatusRepository|null      $pidTuningStatusRepository
     * @param TopicStatusMessageFactory           $topicStatusMessageFactory
     */
    public function __construct(OutputInterface $output, IncomingMessageCreationService $messageService = null, ClientFactory $clientFactory = null, GeoPositionRepository $geoPositionRepository = null, GyroStatusRepository $gyroStatusRepository = null, MotorStatusRepository $motorStatusRepository = null, PidFrequencyStatusRepository $pidFrequencyStatusRepository = null, PidTuningStatusRepository $pidTuningStatusRepository = null, TopicStatusMessageFactory $topicStatusMessageFactory = null)
    {
        parent::__construct($output, $messageService ?: new IncomingMessageCreationService(), $clientFactory ?: new ClientFactory());
        $this->geoPositionRepository = $geoPositionRepository ?: new GeoPositionRepository();
        $this->gyroStatusRepository = $gyroStatusRepository ?: new GyroStatusRepository();
        $this->motorStatusRepository = $motorStatusRepository ?: new MotorStatusRepository();
        $this->pidFrequencyStatusRepository = $pidFrequencyStatusRepository ?: new PidFrequencyStatusRepository();
        $this->pidTuningStatusRepository = $pidTuningStatusRepository ?: new PidTuningStatusRepository();

        $this->repositories[GeoPositionRepository::TOPIC] = $this->geoPositionRepository;
        $this->repositories[GyroStatusRepository::TOPIC] = $this->gyroStatusRepository;
        $this->repositories[MotorStatusRepository::TOPIC] = $this->motorStatusRepository;
        $this->repositories[PidFrequencyStatusRepository::TOPIC] = $this->pidFrequencyStatusRepository;
        $this->repositories[PidTuningStatusRepository::TOPIC] = $this->pidTuningStatusRepository;

        $this->topicStatusMessageFactory = $topicStatusMessageFactory ?: new TopicStatusMessageFactory($this->geoPositionRepository, $this->gyroStatusRepository, $this->motorStatusRepository, $this->pidFrequencyStatusRepository, $this->pidTuningStatusRepository);
    }

    /**
     * @param IncomingMessage $message
     */
    protected function handleMessage(IncomingMessage $message)
    {
        parent::handleMessage($message);

        switch (get_class($message)) {
            case IncomingMotorControlMessage::class:
                /** @var IncomingMotorControlMessage $message */
                foreach ($this->clients as $client) {
                    if ($client->getRole() == ClientRole::FLIGHT_CONTROLLER) {
                        $client->send(json_encode($message->getMotorControl()->toRawMessage()));
                        break;
                    }
                }
                $this->writeDebugLine('MessageRelayService', 'Received motor control message. Redirected to flight controller ...');
                break;
            case IncomingGeoPositionMessage::class:
                /** @var IncomingGeoPositionMessage $message */
                $this->writeDebugLine('MessageRelayService', 'Received geo position message. Saving to repository ...');
                $this->geoPositionRepository->add($message->getGeoPosition());
                $this->fullFillSubscriptions();
                break;
            case IncomingGyroStatusMessage::class:
                /** @var IncomingGyroStatusMessage $message */
                $this->writeDebugLine('MessageRelayService', 'Received gyro status message. Saving to repository ...');
                $this->gyroStatusRepository->add($message->getGyroStatus());
                $this->fullFillSubscriptions();
                break;
            case IncomingMotorStatusMessage::class:
                /** @var IncomingMotorStatusMessage $message */
                $this->writeDebugLine('MessageRelayService', 'Received motor status message. Saving to repository ...');
                $this->motorStatusRepository->add($message->getMotorStatus());
                $this->fullFillSubscriptions();
                break;
            case IncomingPIDFrequencyStatus::class:
                /** @var IncomingPIDFrequencyStatus $message */
                $this->writeDebugLine('MessageRelayService', 'Received PID frequency status message. Saving to repository ...');
                $this->pidFrequencyStatusRepository->add($message->getFrequencyStatus());
                $this->fullFillSubscriptions();
                break;
            case IncomingPIDTuningStatusMessage::class:
                /** @var IncomingPIDTuningStatusMessage $message */
                $this->writeDebugLine('MessageRelayService', 'Received PID tuning status message. Saving to repository ...');
                $this->pidTuningStatusRepository->add($message->getStatus());
                $this->fullFillSubscriptions();
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
                $this->fullFillSubscriptions();
                break;
        }
    }

    protected function fullFillSubscriptions()
    {
        foreach ($this->clients as $client) {
            foreach ($client->getSubscriptions() as $subscription) {
                $this->checkSubscription($client, $subscription);
            }
        }
    }

    /**
     * @param Client      $client
     * @param TopicStatus $subscription
     */
    protected function checkSubscription(Client $client, TopicStatus $subscription)
    {
        $revision = $subscription->getRevision() + 1;
        foreach ($this->repositories[$subscription->getName()]->get($revision) as $message) {
            $client->send(json_encode($message->toRawMessage()));
            $subscription->incrementRevision();
        }
    }

}