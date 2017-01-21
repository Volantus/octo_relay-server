<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Subscription;

use Volante\SkyBukkit\Common\Src\Client\OutgoingMessage;
use Volante\SkyBukkit\Common\Src\General\Network\BaseRawMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicContainer;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatus;

/**
 * Class TopicContainerTest
 *
 * @package Volante\SkyBukkit\RelayServer\Tests\Subscription
 */
class TopicContainerTest extends \PHPUnit_Framework_TestCase
{
    public function test_toRawMessage_correct()
    {
        /** @var OutgoingMessage|\PHPUnit_Framework_MockObject_MockObject $payload */
        $payload = $this->getMockBuilder(OutgoingMessage::class)->disableOriginalConstructor()->getMock();
        $payload->expects(self::once())
            ->method('getRawData')->willReturn(['correctPayload']);

        $topic = new TopicStatus('testTopic', 3);
        $topicContainer = new TopicContainer($topic, $payload);
        $rawMessage = $topicContainer->toRawMessage();

        $expected = [
            'topic'      => $topic,
            'receivedAt' => $topicContainer->getReceivedAt()->format(TopicContainer::DATE_FORMAT),
            'payload'    => ['correctPayload']
        ];

        self::assertInstanceOf(BaseRawMessage::class, $rawMessage);
        self::assertEquals(TopicContainer::TYPE, $rawMessage->getType());
        self::assertEquals('Topic container', $rawMessage->getTitle());
        self::assertEquals($expected, $rawMessage->getData());
    }
}