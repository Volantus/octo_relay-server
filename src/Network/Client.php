<?php
namespace Volante\SkyBukkit\RelayServer\Src\Network;

use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatus;

/**
 * Class Connection
 * @package Volante\SkyBukkit\Monitor\Src\FlightStatus\Network
 */
class Client extends \Volante\SkyBukkit\Common\Src\Server\Network\Client
{
    /**
     * @var TopicStatus[]
     */
    private $subscriptions = [];

    /**
     * @param TopicStatus[] $subscriptions
     */
    public function setSubscriptions(array $subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * @return TopicStatus[]
     */
    public function getSubscriptions(): array
    {
        return $this->subscriptions;
    }
}