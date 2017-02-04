<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Messaging;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Volante\SkyBukkit\Common\Src\General\GeoPosition\GeoPosition;
use Volante\SkyBukkit\Common\Src\General\GeoPosition\IncomingGeoPositionMessage;
use Volante\SkyBukkit\Common\Src\General\GyroStatus\GyroStatus;
use Volante\SkyBukkit\Common\Src\General\GyroStatus\IncomingGyroStatusMessage;
use Volante\SkyBukkit\Common\Src\General\Motor\IncomingMotorStatusMessage;
use Volante\SkyBukkit\Common\Src\General\Motor\Motor;
use Volante\SkyBukkit\Common\Src\General\Motor\MotorStatus;
use Volante\SkyBukkit\Common\Src\General\Role\ClientRole;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageServerService;
use Volante\SkyBukkit\Common\Tests\Server\Messaging\MessageServerServiceTest;
use Volante\SkyBukkit\RelayServer\Src\GeoPosition\GeoPositionRepository;
use Volante\SkyBukkit\RelayServer\Src\GyroStatus\GyroStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\Messaging\IncomingMessageCreationService;
use Volante\SkyBukkit\RelayServer\Src\Messaging\MessageRelayService;
use Volante\SkyBukkit\RelayServer\Src\Motor\MotorStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Network\ClientFactory;
use Volante\SkyBukkit\RelayServer\Src\Subscription\RequestTopicStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\SubscriptionStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicContainer;
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
     * @var GyroStatusRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $gyroStatusRepository;

    /**
     * @var MotorStatusRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $motorStatusRepository;

    /**
     * @var TopicStatusMessageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $topicStatusMessageFactory;

    protected function setUp()
    {
        $this->geoPositionRepository = $this->getMockBuilder(GeoPositionRepository::class)->disableOriginalConstructor()->getMock();
        $this->gyroStatusRepository = $this->getMockBuilder(GyroStatusRepository::class)->disableOriginalConstructor()->getMock();
        $this->motorStatusRepository = $this->getMockBuilder(MotorStatusRepository::class)->disableOriginalConstructor()->getMock();
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
        return new MessageRelayService($this->dummyOutput, $this->messageService, $this->clientFactory, $this->geoPositionRepository, $this->gyroStatusRepository, $this->motorStatusRepository, $this->topicStatusMessageFactory);
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

    public function test_handleMessage_gyroStatusMessage()
    {
        $client = new Client(ClientRole::STATUS_BROKER, $this->connection, -1);
        $client->setAuthenticated();
        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new IncomingGyroStatusMessage($client, new GyroStatus(1, 2, 3)));

        $this->gyroStatusRepository->expects(self::once())
            ->method('add')
            ->with(new GyroStatus(1, 2, 3));

        $this->messageServerService->newClient($this->connection);
        $this->messageServerService->newMessage($this->connection, 'correct');
    }

    public function test_handleMessage_motorStatusMessage()
    {
        $client = new Client(ClientRole::FLIGHT_CONTROLLER, $this->connection, -1);
        $client->setAuthenticated();
        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new IncomingMotorStatusMessage($client, new MotorStatus([new Motor(1, Motor::ZERO_LEVEL, 22)])));

        $this->motorStatusRepository->expects(self::once())
            ->method('add')
            ->with(new MotorStatus([new Motor(1, Motor::ZERO_LEVEL, 22)]));

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

    public function test_handleMessage_geoPositionMessage_subscriptionFullFilled()
    {
        $topicContainer = new TopicContainer(new TopicStatus(GeoPositionRepository::TOPIC, 11), new GeoPosition(1, 2, 3));

        /** @var ConnectionInterface|\PHPUnit_Framework_MockObject_MockObject $connection */
        $connection = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        $connection->expects(self::once())
            ->method('send')
            ->with('{"type":"topicContainer","title":"Topic container","data":{"topic":{"name":"geoPosition","revision":11},"receivedAt":"' . $topicContainer->getReceivedAt()->format(TopicContainer::DATE_FORMAT) . '","payload":{"latitude":1,"longitude":2,"altitude":3}}}');

        $client = new Client(ClientRole::OPERATOR, $connection, -1);
        $client->setSubscriptions([new TopicStatus(GeoPositionRepository::TOPIC, 9)]);
        $client->setAuthenticated();

        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new IncomingGeoPositionMessage($client, new GeoPosition(1, 2, 3)));

        $this->geoPositionRepository->expects(self::once())
            ->method('get')->with(10)
            ->willReturn([$topicContainer]);

        $this->messageServerService->newClient($connection);
        $this->messageServerService->newMessage($connection, 'correct');
        self::assertEquals(10, $client->getSubscriptions()[0]->getRevision());
    }

    public function test_handleMessage_gyroStatusMessage_subscriptionFullFilled()
    {
        $topicContainer = new TopicContainer(new TopicStatus(GyroStatusRepository::TOPIC, 11), new GyroStatus(1, 2, 3));

        /** @var ConnectionInterface|\PHPUnit_Framework_MockObject_MockObject $connection */
        $connection = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        $connection->expects(self::once())
            ->method('send')
            ->with('{"type":"topicContainer","title":"Topic container","data":{"topic":{"name":"gyroStatus","revision":11},"receivedAt":"' . $topicContainer->getReceivedAt()->format(TopicContainer::DATE_FORMAT) . '","payload":{"yaw":1,"pitch":3,"roll":2}}}');

        $client = new Client(ClientRole::OPERATOR, $connection, -1);
        $client->setSubscriptions([new TopicStatus(GyroStatusRepository::TOPIC, 9)]);
        $client->setAuthenticated();

        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new IncomingGyroStatusMessage($client, new GyroStatus(1, 2, 3)));

        $this->gyroStatusRepository->expects(self::once())
            ->method('get')->with(10)
            ->willReturn([$topicContainer]);

        $this->messageServerService->newClient($connection);
        $this->messageServerService->newMessage($connection, 'correct');
        self::assertEquals(10, $client->getSubscriptions()[0]->getRevision());
    }

    public function test_handleMessage_motorStatusMessage_subscriptionFullFilled()
    {
        $topicContainer = new TopicContainer(new TopicStatus(MotorStatusRepository::TOPIC, 11), new MotorStatus([new Motor(1, Motor::ZERO_LEVEL, 22)]));

        /** @var ConnectionInterface|\PHPUnit_Framework_MockObject_MockObject $connection */
        $connection = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        $connection->expects(self::once())
            ->method('send')
            ->with(self::equalTo('{"type":"topicContainer","title":"Topic container","data":{"topic":{"name":"motorStatus","revision":11},"receivedAt":"' . $topicContainer->getReceivedAt()->format(TopicContainer::DATE_FORMAT) . '","payload":{"motors":[{"id":1,"pin":22,"power":1000}]}}}'));

        $client = new Client(ClientRole::OPERATOR, $connection, -1);
        $client->setSubscriptions([new TopicStatus(MotorStatusRepository::TOPIC, 9)]);
        $client->setAuthenticated();

        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with(self::equalTo($client), self::equalTo('correct'))->willReturn(new IncomingMotorStatusMessage($client, new MotorStatus([new Motor(1, Motor::ZERO_LEVEL, 22)])));

        $this->motorStatusRepository->expects(self::once())
            ->method('get')->with(self::equalTo(10))
            ->willReturn([$topicContainer]);

        $this->messageServerService->newClient($connection);
        $this->messageServerService->newMessage($connection, 'correct');
        self::assertEquals(10, $client->getSubscriptions()[0]->getRevision());
    }

    public function test_handleMessage_subscriptionStatus_subscriptionFullFilled()
    {
        $topicContainer = new TopicContainer(new TopicStatus(GeoPositionRepository::TOPIC, 11), new GeoPosition(1, 2, 3));

        /** @var ConnectionInterface|\PHPUnit_Framework_MockObject_MockObject $connection */
        $connection = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        $connection->expects(self::once())
            ->method('send')
            ->with('{"type":"topicContainer","title":"Topic container","data":{"topic":{"name":"geoPosition","revision":11},"receivedAt":"' . $topicContainer->getReceivedAt()->format(TopicContainer::DATE_FORMAT) . '","payload":{"latitude":1,"longitude":2,"altitude":3}}}');

        $client = new Client(ClientRole::OPERATOR, $connection, -1);
        $client->setSubscriptions([new TopicStatus(GeoPositionRepository::TOPIC, 9)]);
        $client->setAuthenticated();

        $this->clientFactory->method('get')->willReturn($client);

        $expectedStatus = [
            new TopicStatus(GeoPositionRepository::TOPIC, 9),
            new TopicStatus('topic2', 2)
        ];

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new SubscriptionStatusMessage($client, $expectedStatus));

        $this->geoPositionRepository->expects(self::once())
            ->method('get')->with(10)
            ->willReturn([$topicContainer]);

        $this->messageServerService->newClient($connection);
        $this->messageServerService->newMessage($connection, 'correct');
        self::assertEquals(10, $client->getSubscriptions()[0]->getRevision());
    }
}