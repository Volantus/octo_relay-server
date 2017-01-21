<?php
namespace Volante\SkyBukkit\RelayServer\Src\Subscription;

use Volante\SkyBukkit\Common\Src\Server\Messaging\IncomingMessage;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageFactory;
use Volante\SkyBukkit\Common\Src\Server\Network\NetworkRawMessage;

/**
 * Class RequestTopicStatusMessageFactory
 *
 * @package Volante\SkyBukkit\RelayServer\Src\Subscription
 */
class RequestTopicStatusMessageFactory extends MessageFactory
{
    /**
     * @var string
     */
    protected $type = RequestTopicStatusMessage::TYPE;

    /**
     * @param NetworkRawMessage $rawMessage
     *
     * @return IncomingMessage
     */
    public function create(NetworkRawMessage $rawMessage): IncomingMessage
    {
        return new RequestTopicStatusMessage($rawMessage->getSender());
    }
}