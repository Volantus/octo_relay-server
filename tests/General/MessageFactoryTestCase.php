<?php
namespace Volante\SkyBukkit\RelayServer\Tests\General;

use Volante\SkyBukkit\RelayServer\Src\Messaging\Message;
use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Network\RawMessage;

/**
 * Class MessageFactoryTestCase
 * @package Volante\SkyBukkit\RelayServer\Tests\General
 */
abstract class MessageFactoryTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
       $this->client = new Client(new DummyConnection(), -1);
    }

    /**
     * @return string
     */
    abstract protected function getMessageType() : string;

    /**
     * @param RawMessage $rawMessage
     * @return mixed
     */
    abstract protected function callFactory(RawMessage $rawMessage) : Message;

    /**
     * @return array
     */
    abstract protected function getCorrectMessageData() : array;

    /**
     * @param string $key
     */
    protected function validateMissingKey(string $key)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid ' . $this->getMessageType() . ' message: ' . $key . ' key is missing');

        $data = $this->getCorrectMessageData();
        unset($data[$key]);
        $message = $this->getRawMessage($data);
        $this->callFactory($message);
    }

    /**
     * @param string $key
     */
    protected function validateNotNumeric(string $key)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid ' . $this->getMessageType() . ' message: value of key ' . $key . ' is not numeric');

        $data = $this->getCorrectMessageData();
        $data[$key] = 'abc';
        $message = $this->getRawMessage($data);
        $this->callFactory($message);
    }

    /**
     * @param string $key
     */
    protected function validateNotString(string $key)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid ' . $this->getMessageType() . ' message: value of key ' . $key . ' is not a string');

        $data = $this->getCorrectMessageData();
        $data[$key] = [];
        $message = $this->getRawMessage($data);
        $this->callFactory($message);
    }

    /**
     * @param array $data
     * @return RawMessage
     */
    protected function getRawMessage(array $data) : RawMessage
    {
        return new RawMessage($this->client, $this->getMessageType(), 'Dummy message', $data);
    }
}