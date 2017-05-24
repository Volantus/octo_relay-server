<?php
namespace Volantus\RelayServer\Src\Network;

use Volantus\RelayServer\Src\Subscription\TopicStatus;

/**
 * Class Connection
 * @package Volantus\Monitor\Src\FlightStatus\Network
 */
class Client extends \Volantus\FlightBase\Src\Server\Network\Client
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