<?php
namespace Volantus\RelayServer\Src\Messaging;

use Volantus\FlightBase\Src\General\FlightController\PIDFrequencyStatusMessageFactory;
use Volantus\FlightBase\Src\General\FlightController\PIDTuningStatusMessageFactory;
use Volantus\FlightBase\Src\General\FlightController\PIDTuningUpdateMessageFactory;
use Volantus\FlightBase\Src\General\GeoPosition\GeoPositionMessageFactory;
use Volantus\FlightBase\Src\General\GyroStatus\GyroStatusMessageFactory;
use Volantus\FlightBase\Src\General\Motor\MotorControlMessageFactory;
use Volantus\FlightBase\Src\General\Motor\MotorStatusMessageFactory;
use Volantus\FlightBase\Src\Server\Authentication\AuthenticationMessageFactory;
use Volantus\FlightBase\Src\Server\Messaging\MessageService;
use Volantus\FlightBase\Src\Server\Network\RawMessageFactory;
use Volantus\FlightBase\Src\Server\Role\IntroductionMessageFactory;
use Volantus\RelayServer\Src\Subscription\RequestTopicStatusMessageFactory;
use Volantus\RelayServer\Src\Subscription\SubscriptionStatusMessageFactory;

/**
 * Class IncomingMessageCreationService
 *
 * @package Volantus\RelayServer\Src\Messaging
 */
class IncomingMessageCreationService extends MessageService
{
    /**
     * IncomingMessageCreationService constructor.
     *
     * @param RawMessageFactory|null             $rawMessageFactory
     * @param IntroductionMessageFactory|null    $introductionMessageFactory
     * @param AuthenticationMessageFactory|null  $authenticationMessageFactory
     * @param GeoPositionMessageFactory|null     $geoPositionMessageFactory
     * @param GyroStatusMessageFactory           $gyroStatusMessageFactory
     * @param MotorStatusMessageFactory          $motorStatusMessageFactory
     * @param PIDFrequencyStatusMessageFactory   $PIDFrequencyStatusMessageFactory
     * @param MotorControlMessageFactory         $motorControlMessageFactory
     * @param PIDTuningStatusMessageFactory|null $PIDTuningStatusMessageFactory
     * @param PIDTuningUpdateMessageFactory|null $PIDTuningUpdateMessageFactory
     * @param RequestTopicStatusMessageFactory   $requestTopicStatusMessageFactory
     * @param SubscriptionStatusMessageFactory   $subscriptionStatusMessageFactory
     */
    public function __construct(RawMessageFactory $rawMessageFactory = null,
        IntroductionMessageFactory $introductionMessageFactory = null,
        AuthenticationMessageFactory $authenticationMessageFactory = null,
        GeoPositionMessageFactory $geoPositionMessageFactory = null,
        GyroStatusMessageFactory $gyroStatusMessageFactory = null,
        MotorStatusMessageFactory $motorStatusMessageFactory = null,
        PIDFrequencyStatusMessageFactory $PIDFrequencyStatusMessageFactory = null,
        MotorControlMessageFactory $motorControlMessageFactory = null,
        PIDTuningStatusMessageFactory $PIDTuningStatusMessageFactory = null,
        PIDTuningUpdateMessageFactory $PIDTuningUpdateMessageFactory = null,
        RequestTopicStatusMessageFactory $requestTopicStatusMessageFactory = null,
        SubscriptionStatusMessageFactory $subscriptionStatusMessageFactory = null
    ) {
        parent::__construct($rawMessageFactory, $introductionMessageFactory, $authenticationMessageFactory, $geoPositionMessageFactory, $gyroStatusMessageFactory, $motorStatusMessageFactory, $PIDFrequencyStatusMessageFactory, $motorControlMessageFactory, $PIDTuningStatusMessageFactory, $PIDTuningUpdateMessageFactory);

        $this->registerFactory($requestTopicStatusMessageFactory ?: new RequestTopicStatusMessageFactory());
        $this->registerFactory($subscriptionStatusMessageFactory ?: new SubscriptionStatusMessageFactory());
    }
}