<?php
namespace Volante\SkyBukkit\RelayServer\Src\Subscription;

use Volante\SkyBukkit\Common\Src\Server\Messaging\IncomingMessage;

/**
 * Class RequestTopicStatusMessage
 *
 * @package Volante\SkyBukkit\RelayServer\Src\Subscription
 */
class RequestTopicStatusMessage extends IncomingMessage
{
    const TYPE = 'requestTopicStatus';
}