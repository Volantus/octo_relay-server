<?php
namespace Volante\SkyBukkit\RleayServer\Tests\Message;

use Volante\SkyBukkit\RelayServer\Src\Network\ClientFactory;
use Volante\SkyBukkit\RelayServer\Tests\General\DummyConnection;

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

    public function test_get_connectionCorrect()
    {
        $connection = new DummyConnection();
        $client = $this->factory->get($connection);

        self::assertSame($connection, $client->getConnection());
    }

    public function test_get_defaultRoleCorrect()
    {
        $connection = new DummyConnection();
        $client = $this->factory->get($connection);

        self::assertEquals(-1, $client->getRole());
    }

    public function test_get_noSubscriptions()
    {
        $connection = new DummyConnection();
        $client = $this->factory->get($connection);

        self::assertEmpty($client->getSubscriptions());
    }
}