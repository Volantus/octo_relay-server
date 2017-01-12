<?php
namespace Volante\SkyBukkit\RleayServer\Tests\Messaging;

use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Network\RawMessage;
use Volante\SkyBukkit\RelayServer\Tests\General\DummyConnection;

/**
 * Class MessageTest
 * @package Volante\SkyBukkit\Monitor\Tests\Message
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $dummyClient;

    protected function setUp()
    {
        $this->dummyClient = new Client(new DummyConnection(), 99);
    }

    public function test_jsonSerialize()
    {
        $expected = [
            'type' => 'testMessage',
            'title' => 'This is a test',
            'data' => [
                'sub01' => [1, 2, 3],
                'sub02' => [4, 5, 6]
            ]
        ];

        $message = new RawMessage($this->dummyClient, $expected['type'], $expected['title'], $expected['data']);
        self::assertEquals($expected, $message->jsonSerialize());
    }
}