<?php
namespace Volante\SkyBukkit\RelayServer\Src\Messaging;

use Volante\SkyBukkit\Common\Src\General\FlightController\PIDFrequencyStatusMessageFactory;
use Volante\SkyBukkit\Common\Src\General\GeoPosition\GeoPositionMessageFactory;
use Volante\SkyBukkit\Common\Src\General\GyroStatus\GyroStatusMessageFactory;
use Volante\SkyBukkit\Common\Src\General\Motor\MotorControlMessageFactory;
use Volante\SkyBukkit\Common\Src\General\Motor\MotorStatusMessageFactory;
use Volante\SkyBukkit\Common\Src\Server\Authentication\AuthenticationMessageFactory;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageService;
use Volante\SkyBukkit\Common\Src\Server\Network\RawMessageFactory;
use Volante\SkyBukkit\Common\Src\Server\Role\IntroductionMessageFactory;
use Volante\SkyBukkit\RelayServer\Src\Subscription\RequestTopicStatusMessageFactory;
use Volante\SkyBukkit\RelayServer\Src\Subscription\SubscriptionStatusMessageFactory;

/**
 * Class IncomingMessageCreationService
 *
 * @package Volante\SkyBukkit\RelayServer\Src\Messaging
 */
class IncomingMessageCreationService extends MessageService
{
    /**
     * IncomingMessageCreationService constructor.
     *
     * @param RawMessageFactory|null            $rawMessageFactory
     * @param IntroductionMessageFactory|null   $introductionMessageFactory
     * @param AuthenticationMessageFactory|null $authenticationMessageFactory
     * @param GeoPositionMessageFactory|null    $geoPositionMessageFactory
     * @param GyroStatusMessageFactory          $gyroStatusMessageFactory
     * @param MotorStatusMessageFactory         $motorStatusMessageFactory
     * @param PIDFrequencyStatusMessageFactory  $PIDFrequencyStatusMessageFactory
     * @param MotorControlMessageFactory        $motorControlMessageFactory
     * @param RequestTopicStatusMessageFactory  $requestTopicStatusMessageFactory
     * @param SubscriptionStatusMessageFactory  $subscriptionStatusMessageFactory
     */
    public function __construct(RawMessageFactory $rawMessageFactory = null,
        IntroductionMessageFactory $introductionMessageFactory = null,
        AuthenticationMessageFactory $authenticationMessageFactory = null,
        GeoPositionMessageFactory $geoPositionMessageFactory = null,
        GyroStatusMessageFactory $gyroStatusMessageFactory = null,
        MotorStatusMessageFactory $motorStatusMessageFactory = null,
        PIDFrequencyStatusMessageFactory $PIDFrequencyStatusMessageFactory = null,
        MotorControlMessageFactory $motorControlMessageFactory = null,
        RequestTopicStatusMessageFactory $requestTopicStatusMessageFactory = null,
        SubscriptionStatusMessageFactory $subscriptionStatusMessageFactory = null
    ) {
        parent::__construct($rawMessageFactory, $introductionMessageFactory, $authenticationMessageFactory, $geoPositionMessageFactory, $gyroStatusMessageFactory, $motorStatusMessageFactory, $PIDFrequencyStatusMessageFactory, $motorControlMessageFactory);

        $this->registerFactory($requestTopicStatusMessageFactory ?: new RequestTopicStatusMessageFactory());
        $this->registerFactory($subscriptionStatusMessageFactory ?: new SubscriptionStatusMessageFactory());
    }
}