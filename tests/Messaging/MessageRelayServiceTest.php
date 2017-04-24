<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Messaging;

use Ratchet\ConnectionInterface;
use Volante\SkyBukkit\Common\Src\General\FlightController\IncomingPIDFrequencyStatus;
use Volante\SkyBukkit\Common\Src\General\FlightController\IncomingPIDTuningStatusMessage;
use Volante\SkyBukkit\Common\Src\General\FlightController\IncomingPIDTuningUpdateMessage;
use Volante\SkyBukkit\Common\Src\General\FlightController\PIDFrequencyStatus;
use Volante\SkyBukkit\Common\Src\General\FlightController\PIDTuningStatus;
use Volante\SkyBukkit\Common\Src\General\FlightController\PIDTuningStatusCollection;
use Volante\SkyBukkit\Common\Src\General\FlightController\PIDTuningUpdateCollection;
use Volante\SkyBukkit\Common\Src\General\GeoPosition\GeoPosition;
use Volante\SkyBukkit\Common\Src\General\GeoPosition\IncomingGeoPositionMessage;
use Volante\SkyBukkit\Common\Src\General\GyroStatus\GyroStatus;
use Volante\SkyBukkit\Common\Src\General\GyroStatus\IncomingGyroStatusMessage;
use Volante\SkyBukkit\Common\Src\General\Motor\IncomingMotorControlMessage;
use Volante\SkyBukkit\Common\Src\General\Motor\IncomingMotorStatusMessage;
use Volante\SkyBukkit\Common\Src\General\Motor\Motor;
use Volante\SkyBukkit\Common\Src\General\Motor\MotorControlMessage;
use Volante\SkyBukkit\Common\Src\General\Motor\MotorStatus;
use Volante\SkyBukkit\Common\Src\General\Role\ClientRole;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageServerService;
use Volante\SkyBukkit\Common\Tests\Server\Messaging\MessageServerServiceTest;
use Volante\SkyBukkit\RelayServer\Src\FlightController\PidFrequencyStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\FlightController\PidTuningStatusRepository;
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
     * @var PidFrequencyStatusRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $pidFrequencyStatusRepository;

    /**
     * @var PidTuningStatusRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $pidTuningStatusRepository;

    /**
     * @var TopicStatusMessageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $topicStatusMessageFactory;

    protected function setUp()
    {
        $this->geoPositionRepository = $this->getMockBuilder(GeoPositionRepository::class)->disableOriginalConstructor()->getMock();
        $this->gyroStatusRepository = $this->getMockBuilder(GyroStatusRepository::class)->disableOriginalConstructor()->getMock();
        $this->motorStatusRepository = $this->getMockBuilder(MotorStatusRepository::class)->disableOriginalConstructor()->getMock();
        $this->pidFrequencyStatusRepository = $this->getMockBuilder(PidFrequencyStatusRepository::class)->disableOriginalConstructor()->getMock();
        $this->pidTuningStatusRepository = $this->getMockBuilder(PidTuningStatusRepository::class)->disableOriginalConstructor()->getMock();
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
        return new MessageRelayService($this->dummyOutput, $this->messageService, $this->clientFactory, $this->geoPositionRepository, $this->gyroStatusRepository, $this->motorStatusRepository, $this->pidFrequencyStatusRepository, $this->pidTuningStatusRepository, $this->topicStatusMessageFactory);
    }

    public function test_handleMessage_motorControlMessage()
    {
        /** @var ConnectionInterface|\PHPUnit_Framework_MockObject_MockObject $fcConnection */
        $fcConnection = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        /** @var ConnectionInterface|\PHPUnit_Framework_MockObject_MockObject $otherConnection */
        $otherConnection = $this->getMockBuilder(ConnectionInterface::class)->getMock();

        $operatorClient = new Client(1, $otherConnection, ClientRole::OPERATOR);
        $operatorClient->setAuthenticated();
        $this->clientFactory->expects(self::at(0))->method('get')->willReturn($operatorClient);

        $statusBroker = new Client(2, $otherConnection, ClientRole::STATUS_BROKER);
        $statusBroker->setAuthenticated();
        $this->clientFactory->expects(self::at(1))->method('get')->willReturn($statusBroker);

        $flightController = new Client(3, $fcConnection, ClientRole::FLIGHT_CONTROLLER);
        $flightController->setAuthenticated();
        $this->clientFactory->expects(self::at(2))->method('get')->willReturn($flightController);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($operatorClient, 'correct')
            ->willReturn(new IncomingMotorControlMessage($operatorClient, new MotorControlMessage(new GyroStatus(1, 2, 3), 0.3, 0.5, true)));

        $otherConnection->expects(self::never())->method('send');
        $fcConnection->expects(self::once())
            ->method('send')
            ->with(self::equalTo('{"type":"motorControl","title":"Motor control","data":{"desiredPosition":{"yaw":1,"pitch":3,"roll":2},"horizontalThrottle":0.3,"verticalThrottle":0.5,"motorsStarted":true}}'));

        $this->messageServerService->newClient($otherConnection);
        $this->messageServerService->newClient($otherConnection);
        $this->messageServerService->newClient($fcConnection);

        $this->messageServerService->newMessage($otherConnection, 'correct');
    }

    public function test_handleMessage_pidTuningUpdateMessage()
    {
        /** @var ConnectionInterface|\PHPUnit_Framework_MockObject_MockObject $fcConnection */
        $fcConnection = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        /** @var ConnectionInterface|\PHPUnit_Framework_MockObject_MockObject $otherConnection */
        $otherConnection = $this->getMockBuilder(ConnectionInterface::class)->getMock();

        $operatorClient = new Client(1, $otherConnection, ClientRole::OPERATOR);
        $operatorClient->setAuthenticated();
        $this->clientFactory->expects(self::at(0))->method('get')->willReturn($operatorClient);

        $statusBroker = new Client(2, $otherConnection, ClientRole::STATUS_BROKER);
        $statusBroker->setAuthenticated();
        $this->clientFactory->expects(self::at(1))->method('get')->willReturn($statusBroker);

        $flightController = new Client(3, $fcConnection, ClientRole::FLIGHT_CONTROLLER);
        $flightController->setAuthenticated();
        $this->clientFactory->expects(self::at(2))->method('get')->willReturn($flightController);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($operatorClient, 'correct')
            ->willReturn(new IncomingPIDTuningUpdateMessage($operatorClient, new PIDTuningUpdateCollection(new PIDTuningStatus(1, 2, 3), new PIDTuningStatus(4, 5, 6), new PIDTuningStatus(7, 8, 9))));

        $otherConnection->expects(self::never())->method('send');
        $fcConnection->expects(self::once())
            ->method('send')
            ->with(self::equalTo('{"type":"pidTuningUpdate","title":"PID tuning update","data":{"yaw":{"Kp":1,"Ki":2,"Kd":3},"roll":{"Kp":4,"Ki":5,"Kd":6},"pitch":{"Kp":7,"Ki":8,"Kd":9}}}'));

        $this->messageServerService->newClient($otherConnection);
        $this->messageServerService->newClient($otherConnection);
        $this->messageServerService->newClient($fcConnection);

        $this->messageServerService->newMessage($otherConnection, 'correct');
    }

    public function test_handleMessage_geoPositionMessage()
    {
        $client = new Client(0, $this->connection, -1);
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
        $client = new Client(0, $this->connection, -1);
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
        $client = new Client(0, $this->connection, -1);
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

    public function test_handleMessage_pidFrequencyStatusMessage()
    {
        $client = new Client(0, $this->connection, -1);
        $client->setAuthenticated();
        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new IncomingPIDFrequencyStatus($client, new PIDFrequencyStatus(1000, 950)));

        $this->pidFrequencyStatusRepository->expects(self::once())
            ->method('add')
            ->with(new PIDFrequencyStatus(1000, 950));

        $this->messageServerService->newClient($this->connection);
        $this->messageServerService->newMessage($this->connection, 'correct');
    }

    public function test_handleMessage_pidTuningStatusMessage()
    {
        $client = new Client(0, $this->connection, -1);
        $client->setAuthenticated();
        $this->clientFactory->method('get')->willReturn($client);

        $pidTuningStatus = new PIDTuningStatusCollection(new PIDTuningStatus(1, 2, 3), new PIDTuningStatus(1, 2, 3), new PIDTuningStatus(1, 2, 3));

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with($client, 'correct')->willReturn(new IncomingPIDTuningStatusMessage($client, $pidTuningStatus));

        $this->pidTuningStatusRepository->expects(self::once())
            ->method('add')
            ->with(self::equalTo($pidTuningStatus));

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

        $client = new Client(0, $connection, -1);
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
        $client = new Client(0, $this->connection, -1);
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

        $client = new Client(0, $connection, -1);
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

        $client = new Client(0, $connection, -1);
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

        $client = new Client(0, $connection, -1);
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

    public function test_handleMessage_pidFrequencyStatusMessage_subscriptionFullFilled()
    {
        $topicContainer = new TopicContainer(new TopicStatus(PidFrequencyStatusRepository::TOPIC, 11), new PIDFrequencyStatus(1000, 950));

        /** @var ConnectionInterface|\PHPUnit_Framework_MockObject_MockObject $connection */
        $connection = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        $connection->expects(self::once())
            ->method('send')
            ->with(self::equalTo('{"type":"topicContainer","title":"Topic container","data":{"topic":{"name":"' . PidFrequencyStatusRepository::TOPIC . '","revision":11},"receivedAt":"' . $topicContainer->getReceivedAt()->format(TopicContainer::DATE_FORMAT) . '","payload":{"desired":1000,"current":950}}}'));

        $client = new Client(0, $connection, -1);
        $client->setSubscriptions([new TopicStatus(PidFrequencyStatusRepository::TOPIC, 9)]);
        $client->setAuthenticated();

        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with(self::equalTo($client), self::equalTo('correct'))->willReturn(new IncomingPIDFrequencyStatus($client, new PIDFrequencyStatus(1000, 950)));

        $this->pidFrequencyStatusRepository->expects(self::once())
            ->method('get')->with(self::equalTo(10))
            ->willReturn([$topicContainer]);

        $this->messageServerService->newClient($connection);
        $this->messageServerService->newMessage($connection, 'correct');
        self::assertEquals(10, $client->getSubscriptions()[0]->getRevision());
    }

    public function test_handleMessage_pidTuningStatusMessage_subscriptionFullFilled()
    {
        $pidTuningStatus = new PIDTuningStatusCollection(new PIDTuningStatus(1, 2, 3), new PIDTuningStatus(1, 2, 3), new PIDTuningStatus(1, 2, 3));
        $topicContainer = new TopicContainer(new TopicStatus(PidTuningStatusRepository::TOPIC, 11), $pidTuningStatus);

        /** @var ConnectionInterface|\PHPUnit_Framework_MockObject_MockObject $connection */
        $connection = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        $connection->expects(self::once())
            ->method('send')
            ->with(self::equalTo('{"type":"topicContainer","title":"Topic container","data":{"topic":{"name":"' . PidTuningStatusRepository::TOPIC . '","revision":11},"receivedAt":"' . $topicContainer->getReceivedAt()->format(TopicContainer::DATE_FORMAT) . '","payload":{"yaw":{"Kp":1,"Ki":2,"Kd":3},"roll":{"Kp":1,"Ki":2,"Kd":3},"pitch":{"Kp":1,"Ki":2,"Kd":3}}}}'));

        $client = new Client(0, $connection, -1);
        $client->setSubscriptions([new TopicStatus(PidTuningStatusRepository::TOPIC, 9)]);
        $client->setAuthenticated();

        $this->clientFactory->method('get')->willReturn($client);

        $this->messageService->expects(self::once())
            ->method('handle')
            ->with(self::equalTo($client), self::equalTo('correct'))->willReturn(new IncomingPIDTuningStatusMessage($client, $pidTuningStatus));

        $this->pidTuningStatusRepository->expects(self::once())
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

        $client = new Client(0, $connection, -1);
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