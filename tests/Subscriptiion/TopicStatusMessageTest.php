<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Subscription;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatus;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatusMessage;

/**
 * Class TopicStatusMessageTest
 * @package Volante\SkyBukkit\RelayServer\Tests\Subscription
 */
class TopicStatusMessageTest extends \PHPUnit_Framework_TestCase
{
    public function test_toRawMessage_correct()
    {
        $topicStatus = [new TopicStatus('dummyTopic', 5)];
        $message = new TopicStatusMessage($topicStatus);
        $rawMessage = $message->toRawMessage();

        self::assertEquals(TopicStatusMessage::TYPE, $rawMessage->getType());
        self::assertEquals('Topic status', $rawMessage->getTitle());
        self::assertEquals(['status' => $topicStatus], $rawMessage->getData());
    }
}