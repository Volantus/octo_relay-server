<?php
namespace Volantus\RelayServer\Src\Subscription;

use Volantus\FlightBase\Src\Server\Messaging\IncomingMessage;
use Volantus\FlightBase\Src\Server\Messaging\Sender;

/**
 * Class SubscriptionStatusMessage
 *
 * @package Volantus\RelayServer\Src\Subscription
 */
class SubscriptionStatusMessage extends IncomingMessage
{
    const TYPE = 'subscriptionStatusMessage';

    /**
     * @var TopicStatus[]
     */
    private $status;

    /**
     * SubscriptionStatusMessage constructor.
     *
     * @param Sender        $sender
     * @param TopicStatus[] $status
     */
    public function __construct(Sender $sender, array $status)
    {
        parent::__construct($sender);
        $this->status = $status;
    }

    /**
     * @return TopicStatus[]
     */
    public function getStatus(): array
    {
        return $this->status;
    }
}