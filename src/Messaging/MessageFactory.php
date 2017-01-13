<?php
namespace Volante\SkyBukkit\RelayServer\Src\Messaging;

use Assert\Assertion;

/**
 * Class MessageFactory
 * @package Volante\SkyBukkit\RelayServer\Src\Messaging
 */
abstract class MessageFactory
{
    /**
     * @var string
     */
    protected $label = 'notDefined';

    /**
     * @param array $data
     * @param string $key
     */
    protected function validateString(array $data, string $key)
    {
        Assertion::keyExists($data, $key, 'Invalid ' . $this->label . ' message: ' . $key . ' key is missing');
        Assertion::string($data[$key], 'Invalid ' . $this->label . ' message: value of key ' . $key . ' is not a string');
    }

    /**
     * @param array $data
     * @param string $key
     */
    protected function validateNumeric(array $data, string $key)
    {
        Assertion::keyExists($data, $key, 'Invalid ' . $this->label . ' message: ' . $key . ' key is missing');
        Assertion::numeric($data[$key], 'Invalid ' . $this->label . ' message: value of key ' . $key . ' is not numeric');
    }
}