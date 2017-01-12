<?php
namespace Volante\SkyBukkit\RleayServer\Tests\Messaging;

use Ratchet\ConnectionInterface;
use Volante\SkyBukkit\RelayServer\Src\Messaging\MessageRelayService;
use Volante\SkyBukkit\RelayServer\Src\Messaging\MessageService;
use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Network\ClientFactory;
use Volante\SkyBukkit\RelayServer\Src\Role\ClientRole;
use Volante\SkyBukkit\RelayServer\Src\Role\IntroductionMessage;
use Volante\SkyBukkit\RelayServer\Tests\General\DummyConnection;

/**
 * Class MessageRelayServiceTest
 * @package Volante\SkyBukkit\RleayServer\Tests
 */
class MessageRelayServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MessageRelayService
     */
    private $relayService;

    /**
     * @var MessageService|\PHPUnit_Framework_MockObject_MockObject
     */
    private $messageService;

    /**
     * @var ClientFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $clientFactory;

    /**
     * @var ConnectionInterface
     */
    private $connection;

    protected function setUp()
    {
        $this->connection = new DummyConnection();
        $this->clientFactory = $this->getMockBuilder(ClientFactory::class)->disableOriginalConstructor()->getMock();
        $this->messageService = $this->getMockBuilder(MessageService::class)->disableOriginalConstructor()->getMock();

        $this->relayService = new MessageRelayService($this->messageService, $this->clientFactory);
    }

    public function test_newClient_factoryCalled()
    {
        $this->clientFactory->expects(self::once())
            ->method('get')->with($this->connection)
            ->willReturn(new Client($this->connection, -1));

        $this->relayService->newClient($this->connection);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage No connected client found!
     */
    public function test_newMessage_clientNotConnected()
    {
        $this->relayService->newMessage($this->connection, '123');
    }

    public function test_newMessage_messageServiceCalled()
    {
        $client = new Client($this->connection, -1);
        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new IntroductionMessage(new Client($this->connection, -1), 99));

        $this->relayService->newClient($this->connection);
        $this->relayService->newMessage($this->connection, 'correct');
    }

    public function test_newMessage_introductionMessageHandledCorrectly()
    {
        $client = new Client($this->connection, -1);
        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new IntroductionMessage($client, ClientRole::OPERATOR));

        $this->relayService->newClient($this->connection);
        $this->relayService->newMessage($this->connection, 'correct');
        self::assertEquals(ClientRole::OPERATOR, $client->getRole());
    }
}