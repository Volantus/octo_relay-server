<?php
namespace Volante\SkyBukkit\RelayServer\Src\Subscription;

use Carbon\Carbon;
use Volante\SkyBukkit\Common\Src\Client\OutgoingMessage;

/**
 * Class TopicMessage
 * @package Volante\SkyBukkit\RelayServer\Src\Subscription
 */
class TopicContainer
{
    /**
     * @var TopicStatus
     */
    private $topic;

    /**
     * @var Carbon
     */
    private $receivedAt;

    /**
     * @var OutgoingMessage
     */
    private $payload;

    /**
     * TopicMessage constructor.
     * @param TopicStatus $topic
     * @param OutgoingMessage $payload
     */
    public function __construct(TopicStatus $topic, OutgoingMessage $payload)
    {
        $this->topic = $topic;
        $this->receivedAt = new Carbon();
        $this->payload = $payload;
    }

    /**
     * @return TopicStatus
     */
    public function getTopic(): TopicStatus
    {
        return $this->topic;
    }

    /**
     * @return Carbon
     */
    public function getReceivedAt(): Carbon
    {
        return $this->receivedAt;
    }

    /**
     * @return OutgoingMessage
     */
    public function getPayload(): OutgoingMessage
    {
        return $this->payload;
    }
}