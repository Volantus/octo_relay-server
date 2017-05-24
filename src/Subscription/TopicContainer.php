<?php
namespace Volantus\RelayServer\Src\Subscription;

use Carbon\Carbon;
use Volantus\FlightBase\Src\Client\OutgoingMessage;

/**
 * Class TopicMessage
 * @package Volantus\RelayServer\Src\Subscription
 */
class TopicContainer extends OutgoingMessage
{
    const DATE_FORMAT = 'Y-m-d H:i:s';
    const TYPE = 'topicContainer';

    /**
     * @var string
     */
    protected $type = self::TYPE;

    /**
     * @var string
     */
    protected $messageTitle = 'Topic container';

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

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return [
            'topic'       => $this->topic,
            'receivedAt'  => $this->receivedAt->format(self::DATE_FORMAT),
            'payload'     => $this->payload->getRawData()
        ];
    }
}