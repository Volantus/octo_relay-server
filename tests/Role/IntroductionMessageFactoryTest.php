<?php
namespace Volante\SkyBukkit\RleayServer\Tests\Role;

use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Network\RawMessage;
use Volante\SkyBukkit\RelayServer\Src\Role\ClientRole;
use Volante\SkyBukkit\RelayServer\Src\Role\IntroductionMessage;
use Volante\SkyBukkit\RelayServer\Src\Role\IntroductionMessageFactory;
use Volante\SkyBukkit\RelayServer\Tests\General\DummyConnection;

/**
 * Class IntroductionMessageFactoryTest
 * @package Volante\SkyBukkit\RleayServer\Tests\Role
 */
class IntroductionMessageFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var IntroductionMessageFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new IntroductionMessageFactory();
        $this->client = new Client(new DummyConnection(), -1);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid introduction message: role key is missing
     */
    public function test_create_roleKeyMissing()
    {
        $rawMessage = $this->getRawMessage([]);
        $this->factory->create($rawMessage);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid introduction message: role is not numeric
     */
    public function test_create_roleNotNumeric()
    {
        $rawMessage = $this->getRawMessage(['role' => 'abc']);
        $this->factory->create($rawMessage);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid introduction message: given role is not supported
     */
    public function test_create_roleNotSupported()
    {
        $rawMessage = $this->getRawMessage(['role' => 99]);
        $this->factory->create($rawMessage);
    }

    public function test_create_messageCorrect()
    {
        $rawMessage = $this->getRawMessage(['role' => ClientRole::OPERATOR]);
        $message = $this->factory->create($rawMessage);

        self::assertInstanceOf(IntroductionMessage::class, $message);
        self::assertSame($this->client, $message->getSender());
        self::assertEquals(ClientRole::OPERATOR, $message->getRole());
    }

    /**
     * @param array $data
     * @return RawMessage
     */
    protected function getRawMessage(array $data) : RawMessage
    {
        return new RawMessage($this->client, IntroductionMessage::TYPE, 'Dummy Introduction message', $data);
    }
}