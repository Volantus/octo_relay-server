<?php
namespace Volante\SkyBukkit\RelayServer\Src\Network;

/**
 * Class MessageFactory
 *
 * @package Volante\SkyBukkit\Monitor\Src\FlightStatus\Network
 */
class RawMessageFactory extends \Volante\SkyBukkit\Common\Src\Network\RawMessageFactory
{
    /**
     * @param Client $sender
     * @param string $json
     * @return RawMessage
     */
    public function create(Client $sender, string $json) : RawMessage
    {
        $json = $this->getJsonData($json);
        $rawMessage = new RawMessage($sender, $json['type'], $json['title'], $json['data']);

        return $rawMessage;
    }
}