<?php
namespace Volantus\RelayServer\Tests\Subscription;
use Volantus\RelayServer\Src\Subscription\TopicStatus;
use Volantus\RelayServer\Src\Subscription\TopicStatusMessage;

/**
 * Class TopicStatusMessageTest
 * @package Volantus\RelayServer\Tests\Subscription
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