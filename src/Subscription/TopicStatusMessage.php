<?php
namespace Volantus\RelayServer\Src\Subscription;

use Volantus\FlightBase\Src\Client\OutgoingMessage;

/**
 * Class TopicStatusMessage
 * @package Volantus\RelayServer\Src\Subscription
 */
class TopicStatusMessage extends OutgoingMessage
{
    const TYPE = 'topicStatus';

    /**
     * @var string
     */
    protected $type = self::TYPE;

    /**
     * @var string
     */
    protected $messageTitle = 'Topic status';

    /**
     * @var TopicStatus[]
     */
    private $status;

    /**
     * TopicStatusMessage constructor.
     * @param TopicStatus[] $status
     */
    public function __construct(array $status)
    {
        $this->status = $status;
    }

    /**
     * @return TopicStatus[]
     */
    public function getStatus(): array
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return [
            'status' => $this->status
        ];
    }
}