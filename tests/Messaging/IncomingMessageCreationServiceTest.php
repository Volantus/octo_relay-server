<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Messaging;

use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageService;
use Volante\SkyBukkit\Common\Src\Server\Network\NetworkRawMessage;
use Volante\SkyBukkit\Common\Tests\Server\Messaging\MessageServiceTest;
use Volante\SkyBukkit\RelayServer\Src\Messaging\IncomingMessageCreationService;
use Volante\SkyBukkit\RelayServer\Src\Subscription\RequestTopicStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\RequestTopicStatusMessageFactory;
use Volante\SkyBukkit\RelayServer\Src\Subscription\SubscriptionStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\SubscriptionStatusMessageFactory;

/**
 * Class IncomingMessageCreationServiceTest
 *
 * @package Volante\SkyBukkit\RelayServer\Tests\Messaging
 */
class IncomingMessageCreationServiceTest extends MessageServiceTest
{
    /**
     * @var IncomingMessageCreationService
     */
    protected $service;

    /**
     * @var RequestTopicStatusMessageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestTopicStatusMessageFactory;

    /**
     * @var SubscriptionStatusMessageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subscriptionStatusMessageFactory;

    protected function setUp()
    {
        $this->requestTopicStatusMessageFactory = $this->getMockBuilder(RequestTopicStatusMessageFactory::class)->setMethods(['create'])->disableOriginalConstructor()->getMock();
        $this->subscriptionStatusMessageFactory = $this->getMockBuilder(SubscriptionStatusMessageFactory::class)->setMethods(['create'])->disableOriginalConstructor()->getMock();
        parent::setUp();
    }

    /**
     * @return IncomingMessageCreationService|MessageService
     */
    protected function createService() : MessageService
    {
        return new IncomingMessageCreationService($this->rawMessageFactory, $this->introductionMessageFactory, $this->authenticationMessageFactory, $this->geoPositionMessageFactory, $this->gyroStatusMessageFactory, $this->motorStatusMessageFactory, $this->PIDFrequencyStatusMessageFactory, $this->motorControlMessageFactory, $this->pidTuningStatusMessageFactory, $this->pidTuningUpdateMessageFactory, $this->requestTopicStatusMessageFactory, $this->subscriptionStatusMessageFactory);
    }

    public function test_handle_RequestTopicStatusMessageHandledCorrectly()
    {
        $rawMessage = new NetworkRawMessage($this->sender, RequestTopicStatusMessage::TYPE, 'test', []);
        $expected = new RequestTopicStatusMessage($this->sender);

        $this->rawMessageFactory->expects(self::once())
            ->method('create')
            ->with($this->sender, 'correct')
            ->willReturn($rawMessage);
        $this->requestTopicStatusMessageFactory->expects(self::once())->method('create')->willReturn($expected);

        $result = $this->service->handle($this->sender, 'correct');

        self::assertInstanceOf(RequestTopicStatusMessage::class, $result);
        self::assertSame($expected, $result);
    }

    public function test_handle_subscriptionStatusMessageHandledCorrectly()
    {
        $rawMessage = new NetworkRawMessage($this->sender, SubscriptionStatusMessage::TYPE, 'test', ['status' => []]);
        $expected = new SubscriptionStatusMessage($this->sender, []);

        $this->rawMessageFactory->expects(self::once())
            ->method('create')
            ->with($this->sender, 'correct')
            ->willReturn($rawMessage);
        $this->subscriptionStatusMessageFactory->expects(self::once())->method('create')->willReturn($expected);

        $result = $this->service->handle($this->sender, 'correct');

        self::assertInstanceOf(SubscriptionStatusMessage::class, $result);
        self::assertSame($expected, $result);
    }
}