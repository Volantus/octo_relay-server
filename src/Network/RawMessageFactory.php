<?php
namespace Volante\SkyBukkit\RelayServer\Src\Network;

use Assert\Assertion;

/**
 * Class MessageFactory
 *
 * @package Volante\SkyBukkit\Monitor\Src\FlightStatus\Network
 */
class RawMessageFactory
{
    /**
     * @param Client $sender
     * @param string $json
     * @return RawMessage
     */
    public function create(Client $sender, string $json) : RawMessage
    {
        $json = json_decode($json, true);

        Assertion::isArray($json, 'Invalid message format: invalid json format');
        $this->validate($json);

        return new RawMessage($sender, $json['type'], $json['title'], $json['data']);
    }

    /**
     * @param array $data
     */
    private function validate(array $data)
    {
        Assertion::keyExists($data, 'type', 'Invalid message format: attribute <type> missing');
        Assertion::keyExists($data, 'title', 'Invalid message format: attribute <title> missing');
        Assertion::keyExists($data, 'data', 'Invalid message format: attribute <data> missing');

        Assertion::notEmpty($data['type'], 'Invalid message format: attribute <type> is empty');
        Assertion::string($data['type'], 'Invalid message format: attribute <type> is not a string');

        Assertion::notEmpty($data['title'], 'Invalid message format: attribute <title> is empty');
        Assertion::string($data['title'], 'Invalid message format: attribute <title> is not a string');

        Assertion::isArray($data['data'], 'Invalid message format: attribute <data> is not a array');
    }
}