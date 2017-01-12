<?php
namespace Volante\SkyBukkit\RelayServer\Src\Network;

/**
 * Class Message
 * @package Volante\SkyBukkit\Monitor\Src\FlightStatus\Network
 */
class Message implements \JsonSerializable
{
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
     * @param string $type
     * @param string $title
     * @param array $data
     */
    public function __construct(string $type, string $title, array $data)
    {
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