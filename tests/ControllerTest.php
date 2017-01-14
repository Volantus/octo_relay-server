<?php
namespace Volante\SkyBukkit\RelayServer\Tests;

use Symfony\Component\Console\Tests\Fixtures\DummyOutput;
use Volante\SkyBukkit\RelayServer\Src\Controller;
use Volante\SkyBukkit\RelayServer\Src\Messaging\MessageRelayService;
use Volante\SkyBukkit\RelayServer\Tests\General\DummyConnection;

/**
 * Class ControllerTest
 * @package Volante\SkyBukkit\RelayServer\Tests
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MessageRelayService|\PHPUnit_Framework_MockObject_MockObject
     */
    private $relayService;

    /**
     * @var Controller
     */
    private $controller;

    protected function setUp()
    {
        $this->relayService = $this->getMockBuilder(MessageRelayService::class)->disableOriginalConstructor()->getMock();
        $this->controller = new Controller(new DummyOutput(), $this->relayService);
    }

    public function test_onOpen_serviceCalled()
    {
        $connection = new DummyConnection();
        $this->relayService->expects(self::once())
            ->method('newClient')->with($connection);

        $this->controller->onOpen($connection);
    }

    public function test_onMessage_serviceCalled()
    {
        $connection = new DummyConnection();
        $this->relayService->expects(self::once())
            ->method('newMessage')->with($connection, 'correct');

        $this->controller->onMessage($connection, 'correct');
    }

    public function test_onClose_serviceCalled()
    {
        $connection = new DummyConnection();
        $this->relayService->expects(self::once())
            ->method('removeClient')->with($connection);

        $this->controller->onClose($connection);
    }

    public function test_onError_serviceCalled()
    {
        $connection = new DummyConnection();
        $this->relayService->expects(self::once())
            ->method('removeClient')->with($connection);

        $this->controller->onError($connection, new \RuntimeException('test'));
    }
}