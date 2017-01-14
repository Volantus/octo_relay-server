<?php
namespace Volante\SkyBukkit\RleayServer\Tests\Messaging;

use Symfony\Component\Console\Tests\Fixtures\DummyOutput;
use Volante\SkyBukkit\RelayServer\Src\Authentication\AuthenticationMessage;
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
     * @var DummyConnection
     */
    private $connection;

    /**
     * @var DummyOutput|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dummyOutput;

    protected function setUp()
    {
        $this->connection = new DummyConnection();
        $this->clientFactory = $this->getMockBuilder(ClientFactory::class)->disableOriginalConstructor()->getMock();
        $this->messageService = $this->getMockBuilder(MessageService::class)->disableOriginalConstructor()->getMock();

        $this->dummyOutput = $this->getMockBuilder(DummyOutput::class)->disableOriginalConstructor()->getMock();
        $this->relayService = new MessageRelayService($this->dummyOutput, $this->messageService, $this->clientFactory);
    }

    public function test_newClient_factoryCalled()
    {
        $this->clientFactory->expects(self::once())
            ->method('get')->with($this->connection)
            ->willReturn(new Client(1, $this->connection, -1));

        $this->relayService->newClient($this->connection);
    }

    public function test_removeClient_disconnected()
    {
        $this->clientFactory->expects(self::once())
            ->method('get')->with($this->connection)
            ->willReturn(new Client(1, $this->connection, -1));

        $this->relayService->newClient($this->connection);
        $this->relayService->removeClient($this->connection);

        self::assertTrue($this->connection->isConnectionClosed());
    }

    public function test_newMessage_clientNotConnected()
    {
        $outputLog = null;
        $this->dummyOutput->expects(self::once())
            ->method('writeLn')->willReturnCallback(function ($messages, $options = 0) use ($outputLog) {
                self::assertStringEndsWith('[<fg=cyan;options=bold>MessageRelayService</>] <error>No connected client found!</error>', $messages);
            });

        $this->relayService->newMessage($this->connection, '123');
    }

    public function test_newMessage_messageServiceCalled()
    {
        $client = new Client(1, $this->connection, -1);
        $client->setAuthenticated();
        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new IntroductionMessage(new Client(1, $this->connection, -1), 99));

        $this->relayService->newClient($this->connection);
        $this->relayService->newMessage($this->connection, 'correct');
    }

    public function test_newMessage_introductionMessageHandledCorrectly()
    {
        $client = new Client(1, $this->connection, -1);
        $client->setAuthenticated();
        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new IntroductionMessage($client, ClientRole::OPERATOR));

        $this->relayService->newClient($this->connection);
        $this->relayService->newMessage($this->connection, 'correct');
        self::assertEquals(ClientRole::OPERATOR, $client->getRole());
    }

    public function test_newMessage_noAuthentication()
    {
        $client = new Client(1, $this->connection, -1);
        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new IntroductionMessage($client, ClientRole::OPERATOR));

        $this->relayService->newClient($this->connection);
        $this->relayService->newMessage($this->connection, 'correct');
        self::assertTrue($this->connection->isConnectionClosed());
    }

    public function test_newMessage_authenticationMessageHandledCorrectly_tokenWrong()
    {
        $client = new Client(1, $this->connection, -1);
        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new AuthenticationMessage($client, 'wrongToken'));

        $this->relayService->newClient($this->connection);
        $this->relayService->newMessage($this->connection, 'correct');
        self::assertFalse($client->isAuthenticated());
        self::assertTrue($this->connection->isConnectionClosed());
    }

    public function test_newMessage_authenticationMessageHandledCorrectly_tokenCorrect()
    {
        $client = new Client(1, $this->connection, -1);
        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new AuthenticationMessage($client, 'correctToken'));

        $this->relayService->newClient($this->connection);
        $this->relayService->newMessage($this->connection, 'correct');
        self::assertTrue($client->isAuthenticated());
    }
}