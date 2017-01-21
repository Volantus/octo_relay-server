<?php
namespace Volante\SkyBukkit\RelayServer\Src\Subscription;

use Volante\SkyBukkit\Common\Src\Server\Messaging\IncomingMessage;
use Volante\SkyBukkit\Common\Src\Server\Messaging\Sender;

/**
 * Class SubscriptionStatusMessage
 *
 * @package Volante\SkyBukkit\RelayServer\Src\Subscription
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