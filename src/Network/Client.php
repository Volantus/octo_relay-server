<?php
namespace Volante\SkyBukkit\RelayServer\Src\Network;

use Volante\SkyBukkit\RelayServer\Src\Subscription\Topic;

/**
 * Class Connection
 * @package Volante\SkyBukkit\Monitor\Src\FlightStatus\Network
 */
class Client extends \Volante\SkyBukkit\Common\Src\Server\Network\Client
{
    /**
     * @var Topic[]
     */
    private $subscriptions = [];

    /**
     * @return Topic[]
     */
    public function getSubscriptions(): array
    {
        return $this->subscriptions;
    }
}