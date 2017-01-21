<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Messaging;

use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageService;
use Volante\SkyBukkit\Common\Src\Server\Network\NetworkRawMessage;
use Volante\SkyBukkit\Common\Tests\Server\Messaging\MessageServiceTest;
use Volante\SkyBukkit\RelayServer\Src\Messaging\IncomingMessageCreationService;
use Volante\SkyBukkit\RelayServer\Src\Subscription\RequestTopicStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\RequestTopicStatusMessageFactory;

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

    protected function setUp()
    {
        $this->requestTopicStatusMessageFactory = $this->getMockBuilder(RequestTopicStatusMessageFactory::class)->setMethods(['create'])->disableOriginalConstructor()->getMock();
        parent::setUp();
    }

    /**
     * @return IncomingMessageCreationService|MessageService
     */
    protected function createService() : MessageService
    {
        return new IncomingMessageCreationService($this->rawMessageFactory, $this->introductionMessageFactory, $this->authenticationMessageFactory, $this->geoPositionMessageFactory, $this->requestTopicStatusMessageFactory);
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
}