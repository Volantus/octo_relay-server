<?php
namespace Volantus\RelayServer\Src\Subscription;

use Volantus\FlightBase\Src\Server\Messaging\IncomingMessage;

/**
 * Class RequestTopicStatusMessage
 *
 * @package Volantus\RelayServer\Src\Subscription
 */
class RequestTopicStatusMessage extends IncomingMessage
{
    const TYPE = 'requestTopicStatus';
}