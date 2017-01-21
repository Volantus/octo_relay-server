<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Messaging;

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

    protected function setUp()
    {
        $this->geoPositionRepository = $this->getMockBuilder(GeoPositionRepository::class)->disableOriginalConstructor()->getMock();
        parent::setUp();
    }

    /**
     * @return MessageServerService
     */
    protected function createService(): MessageServerService
    {
        $this->clientFactory = $this->getMockBuilder(ClientFactory::class)->disableOriginalConstructor()->getMock();
        $this->messageService = $this->getMockBuilder(IncomingMessageCreationService::class)->disableOriginalConstructor()->getMock();
        return new MessageRelayService($this->dummyOutput, $this->messageService, $this->clientFactory, $this->geoPositionRepository);
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
}