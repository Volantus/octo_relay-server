<?php
namespace Volante\SkyBukkit\RleayServer\Tests\Messaging;

use Volante\SkyBukkit\RelayServer\Src\Messaging\MessageService;
use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Messaging\Message;
use Volante\SkyBukkit\RelayServer\Src\Network\RawMessage;
use Volante\SkyBukkit\RelayServer\Src\Network\RawMessageFactory;
use Volante\SkyBukkit\RelayServer\Src\Role\IntroductionMessage;
use Volante\SkyBukkit\RelayServer\Src\Role\IntroductionMessageFactory;
use Volante\SkyBukkit\RelayServer\Tests\General\DummyConnection;

/**
 * Class MessageServiceIntegrationTest
 * @package Volante\SkyBukkit\RleayServer\Tests
 */
class MessageServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MessageService
     */
    private $service;

    /**
     * @var RawMessageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $rawMessageFactory;

    /**
     * @var IntroductionMessageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $introductionMessageFactory;

    /**
     * @var Client
     */
    private $sender;

    protected function setUp()
    {
        $this->sender = new Client(new DummyConnection(), -1);
        $this->rawMessageFactory = $this->getMockBuilder(RawMessageFactory::class)->disableOriginalConstructor()->getMock();
        $this->introductionMessageFactory = $this->getMockBuilder(IntroductionMessageFactory::class)->disableOriginalConstructor()->getMock();

        $this->service = new MessageService($this->rawMessageFactory, $this->introductionMessageFactory);
    }

    public function test_handle_rawMessageServiceCalled()
    {
        $this->rawMessageFactory->expects(self::once())
            ->method('create')
            ->with($this->sender, 'correct')
            ->willReturn(new RawMessage($this->sender, IntroductionMessage::TYPE, 'test', []));
        $this->introductionMessageFactory->method('create')->willReturn(new IntroductionMessage($this->sender, 99));

        $this->service->handle($this->sender, 'correct');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to handle message: given type <invalidMessageType> is unknown
     */
    public function test_handle_invalidMessageType()
    {
        $this->rawMessageFactory->expects(self::once())
            ->method('create')
            ->with($this->sender, 'correct')
            ->willReturn(new RawMessage($this->sender, 'invalidMessageType', 'test', []));

        $this->service->handle($this->sender, 'correct');
    }

    public function test_handle_introductionMessageHandledCorrectly()
    {
        $rawMessage = new RawMessage($this->sender, IntroductionMessage::TYPE, 'test', []);
        $expected = new IntroductionMessage($this->sender, 99);

        $this->rawMessageFactory->expects(self::once())
            ->method('create')
            ->with($this->sender, 'correct')
            ->willReturn($rawMessage);
        $this->introductionMessageFactory->expects(self::once())->method('create')->willReturn($expected);

        $result = $this->service->handle($this->sender, 'correct');

        self::assertInstanceOf(Message::class, $result);
        self::assertSame($expected, $result);
    }
}