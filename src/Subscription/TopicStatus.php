<?php
namespace Volantus\RelayServer\Src\Subscription;

/**
 * Class Subscription
 * @package Volantus\Monitor\Src\Subscription
 */
class TopicStatus implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    protected $revision;

    /**
     * Topic constructor.
     * @param string $name
     * @param int $revision
     */
    public function __construct(string $name, int $revision)
    {
        $this->name = $name;
        $this->revision = $revision;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getRevision(): int
    {
        return $this->revision;
    }

    public function incrementRevision()
    {
        $this->revision++;
    }

    /**
     * @inheritdoc
     */
    function jsonSerialize()
    {
        return [
            'name'     => $this->name,
            'revision' => $this->revision
        ];
    }
}