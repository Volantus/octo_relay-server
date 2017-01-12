<?php
namespace Volante\SkyBukkit\RelayServer\Src\Network;

/**
 * Class Message
 * @package Volante\SkyBukkit\Monitor\Src\FlightStatus\Network
 */
class RawMessage implements \JsonSerializable
{
    /**
     * @var Client
     */
    private $sender;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $data;

    /**
     * Message constructor.
     * @param Client $client
     * @param string $type
     * @param string $title
     * @param array $data
     */
    public function __construct(Client $client, string $type, string $title, array $data)
    {
        $this->sender = $client;
        $this->type = $type;
        $this->title = $title;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return Client
     */
    public function getSender(): Client
    {
        return $this->sender;
    }

    /**
     * @inheritdoc
     */
    function jsonSerialize()
    {
        return [
            'type'  => $this->type,
            'title' => $this->title,
            'data'  => $this->data
        ];
    }
}