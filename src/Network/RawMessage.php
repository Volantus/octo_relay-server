<?php
namespace Volante\SkyBukkit\RelayServer\Src\Network;

/**
 * Class Message
 * @package Volante\SkyBukkit\Monitor\Src\FlightStatus\Network
 */
class RawMessage extends \Volante\SkyBukkit\Common\Src\General\Network\RawMessage
{
    /**
     * @var Client
     */
    private $sender;

    /**
     * Message constructor.
     * @param Client $client
     * @param string $type
     * @param string $title
     * @param array $data
     * @internal param ClientInterface $client
     */
    public function __construct(Client $client, string $type, string $title, array $data)
    {
        parent::__construct($type, $title, $data);
        $this->sender = $client;
    }

    /**
     * @return Client
     */
    public function getSender(): Client
    {
        return $this->sender;
    }
}