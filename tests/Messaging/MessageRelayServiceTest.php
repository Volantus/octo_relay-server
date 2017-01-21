<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Messaging;

use Ratchet\ConnectionInterface;
use Volante\SkyBukkit\Common\Src\General\GeoPosition\GeoPosition;
use Volante\SkyBukkit\Common\Src\General\GeoPosition\IncomingGeoPositionMessage;
use Volante\SkyBukkit\Common\Src\General\Role\ClientRole;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageServerService;
use Volante\SkyBukkit\Common\Tests\Server\Messaging\MessageServerServiceTest;
use Volante\SkyBukkit\RelayServer\Src\GeoPosition\GeoPositionRepository;
use Volante\SkyBukkit\RelayServer\Src\Messaging\IncomingMessageCreationService;
use Volante\SkyBukkit\RelayServer\Src\Messaging\MessageRelayService;
use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Network\ClientFactory;
use Volante\SkyBukkit\RelayServer\Src\Subscription\RequestTopicStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\SubscriptionStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatus;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatusMessageFactory;

/**
 * Class MessageRelayServiceTest
 * @package Volante\SkyBukkit\RelayServer\Tests\Messaging
 */
class MessageRelayServiceTest extends MessageServerServiceTest
{
    /**
     * @var GeoPositionRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $geoPositionRepository;

    /**
     * @var TopicStatusMessageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $topicStatusMessageFactory;

    protected function setUp()
    {
        $this->geoPositionRepository = $this->getMockBuilder(GeoPositionRepository::class)->disableOriginalConstructor()->getMock();
        $this->topicStatusMessageFactory = $this->getMockBuilder(TopicStatusMessageFactory::class)->disableOriginalConstructor()->getMock();
        parent::setUp();
    }

    /**
     * @return MessageServerService
     */
    protected function createService(): MessageServerService
    {
        $this->clientFactory = $this->getMockBuilder(ClientFactory::class)->disableOriginalConstructor()->getMock();
        $this->messageService = $this->getMockBuilder(IncomingMessageCreationService::class)->disableOriginalConstructor()->getMock();
        return new MessageRelayService($this->dummyOutput, $this->messageService, $this->clientFactory, $this->geoPositionRepository, $this->topicStatusMessageFactory);
    }

    public function test_handleMessage_geoPositionMessage()
    {
        $client = new Client(ClientRole::STATUS_BROKER, $this->connection, -1);
        $client->setAuthenticated();
        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new IncomingGeoPositionMessage($client, new GeoPosition(1, 2, 3)));

        $this->geoPositionRepository->expects(self::once())
            ->method('add')
            ->with(new GeoPosition(1, 2, 3));

        $this->messageServerService->newClient($this->connection);
        $this->messageServerService->newMessage($this->connection, 'correct');
    }

    public function test_handleMessage_requestTopicStatus()
    {
        /** @var ConnectionInterface|\PHPUnit_Framework_MockObject_MockObject $connection */
        $connection = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        $connection->expects(self::once())
            ->method('send')
            ->with('{"type":"topicStatus","title":"Topic status","data":{"status":[{"name":"dummyTopic","revision":4}]}}');

        $client = new Client(ClientRole::OPERATOR, $connection, -1);
        $client->setAuthenticated();
        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new RequestTopicStatusMessage($client));

        $this->topicStatusMessageFactory->expects(self::once())
            ->method('getMessage')
            ->willReturn(new TopicStatusMessage([new TopicStatus('dummyTopic', 4)]));

        $this->messageServerService->newClient($connection);
        $this->messageServerService->newMessage($connection, 'correct');
    }

    public function test_handleMessage_subscriptionStatusHandledCorrect()
    {
        $client = new Client(ClientRole::OPERATOR, $this->connection, -1);
        $client->setAuthenticated();
        $this->clientFactory->method('get')->willReturn($client);

        $expectedStatus = [
            new TopicStatus('topic1', 1),
            new TopicStatus('topic2', 2)
        ];

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new SubscriptionStatusMessage($client, $expectedStatus));

        $this->messageServerService->newClient($this->connection);
        $this->messageServerService->newMessage($this->connection, 'correct');
        self::assertEquals($expectedStatus, $client->getSubscriptions());
    }
}