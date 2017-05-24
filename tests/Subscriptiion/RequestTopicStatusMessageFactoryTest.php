<?php
namespace Volantus\RelayServer\Tests\Subscription;

use Volantus\FlightBase\Src\General\Network\BaseRawMessage;
use Volantus\FlightBase\Src\Server\Network\NetworkRawMessage;
use Volantus\FlightBase\Tests\Server\General\DummyConnection;
use Volantus\RelayServer\Src\Network\Client;
use Volantus\RelayServer\Src\Subscription\RequestTopicStatusMessage;
use Volantus\RelayServer\Src\Subscription\RequestTopicStatusMessageFactory;

/**
 * Class RequestTopicStatusMessageFactoryTest
 *
 * @package Volantus\RelayServer\Tests\Subscription
 */
class RequestTopicStatusMessageFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RequestTopicStatusMessageFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new RequestTopicStatusMessageFactory();
    }

    public function test_getType_correct()
    {
        self::assertEquals(RequestTopicStatusMessage::TYPE, $this->factory->getType());
    }

    public function test_create_correct()
    {
        $sender = new Client(1, new DummyConnection(), 99);
        $result = $this->factory->create(new NetworkRawMessage($sender, RequestTopicStatusMessage::TYPE, 'dummyTitle', []));

        self::assertInstanceOf(RequestTopicStatusMessage::class, $result);
        self::assertSame($sender, $result->getSender());
    }
}