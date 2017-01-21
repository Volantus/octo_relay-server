<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Subscription;

use Volante\SkyBukkit\Common\Src\General\Network\BaseRawMessage;
use Volante\SkyBukkit\Common\Src\Server\Network\NetworkRawMessage;
use Volante\SkyBukkit\Common\Tests\Server\General\DummyConnection;
use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Subscription\RequestTopicStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\RequestTopicStatusMessageFactory;

/**
 * Class RequestTopicStatusMessageFactoryTest
 *
 * @package Volante\SkyBukkit\RelayServer\Tests\Subscription
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