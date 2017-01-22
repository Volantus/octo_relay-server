<?php
namespace Volante\SkyBukkit\RelayServer\Src\Subscription;

use Volante\SkyBukkit\Common\Src\Server\Messaging\IncomingMessage;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageFactory;
use Volante\SkyBukkit\Common\Src\Server\Network\NetworkRawMessage;

/**
 * Class SubscriptionStatusMessageFactory
 *
 * @package Volante\SkyBukkit\RelayServer\Src\Subscription
 */
class SubscriptionStatusMessageFactory extends MessageFactory
{
    /**
     * @var string
     */
    protected $type = SubscriptionStatusMessage::TYPE;

    /**
     * @param NetworkRawMessage $rawMessage
     *
     * @return IncomingMessage
     */
    public function create(NetworkRawMessage $rawMessage): IncomingMessage
    {
        $data = $rawMessage->getData();
        $status = [];

        $this->validateArray($data, 'status');
        foreach ($data['status'] as $statusData) {
            $this->validateString($statusData, 'name');
            $this->validateNumeric($statusData, 'revision');

            $status[] = new TopicStatus($statusData['name'], $statusData['revision']);
        }

        return new SubscriptionStatusMessage($rawMessage->getSender(), $status);
    }
}