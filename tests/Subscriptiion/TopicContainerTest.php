<?php
namespace Volantus\RelayServer\Tests\Subscription;

use Volantus\FlightBase\Src\Client\OutgoingMessage;
use Volantus\FlightBase\Src\General\Network\BaseRawMessage;
use Volantus\RelayServer\Src\Subscription\TopicContainer;
use Volantus\RelayServer\Src\Subscription\TopicStatus;

/**
 * Class TopicContainerTest
 *
 * @package Volantus\RelayServer\Tests\Subscription
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