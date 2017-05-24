<?php
namespace Volantus\RleayServer\Tests\Message;
use Volantus\FlightBase\Tests\Server\General\DummyConnection;
use Volantus\RelayServer\Src\Network\ClientFactory;

/**
 * Class ClientFactoryTest
 * @package Volantus\RleayServer\Tests\Message
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