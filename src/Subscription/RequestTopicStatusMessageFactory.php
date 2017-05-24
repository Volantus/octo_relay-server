<?php
namespace Volantus\RelayServer\Src\Subscription;

use Volantus\FlightBase\Src\Server\Messaging\IncomingMessage;
use Volantus\FlightBase\Src\Server\Messaging\MessageFactory;
use Volantus\FlightBase\Src\Server\Network\NetworkRawMessage;

/**
 * Class RequestTopicStatusMessageFactory
 *
 * @package Volantus\RelayServer\Src\Subscription
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