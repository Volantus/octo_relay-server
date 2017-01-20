<?php
namespace Volante\SkyBukkit\RelayServer\Src\Subscription;

/**
 * Class Subscription
 * @package Volante\SkyBukkit\Monitor\Src\Subscription
 */
class TopicStatus
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
}