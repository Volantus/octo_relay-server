<?php
namespace Volante\SkyBukkit\RleayServer\Tests\Message;
use Volante\SkyBukkit\Common\Tests\Server\General\DummyConnection;
use Volante\SkyBukkit\RelayServer\Src\Network\ClientFactory;

/**
 * Class ClientFactoryTest
 * @package Volante\SkyBukkit\RleayServer\Tests\Message
 */
class ClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClientFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new ClientFactory();
    }

    public function test_get_noSubscriptions()
    {
        $connection = new DummyConnection();
        $client = $this->factory->get($connection);

        self::assertEmpty($client->getSubscriptions());
    }
}