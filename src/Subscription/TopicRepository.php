<?php
namespace Volante\SkyBukkit\RelayServer\Src\Subscription;
use Volante\SkyBukkit\Common\Src\Client\OutgoingMessage;

/**
 * Class TopicRepository
 * @package Volante\SkyBukkit\RelayServer\Src\Subscription
 */
abstract class TopicRepository
{
    /**
     * @var int
     */
    private $holdCount;

    /**
     * @var TopicStatus
     */
    private $topicStatus;

    /**
     * @var TopicContainer[]
     */
    private $containers = [];

    /**
     * @var int
     */
    private $referenceOffset = 0;

    /**
     * TopicRepository constructor.
     * @param int $holdCount
     * @param string $topicName
     */
    public function __construct(int $holdCount, string $topicName)
    {
        $this->holdCount = $holdCount;
        $this->topicStatus = new TopicStatus($topicName, 0);
    }

    /**
     * @param OutgoingMessage $message
     */
    public function add(OutgoingMessage $message)
    {
        $this->containers[] = new TopicContainer(clone $this->topicStatus, $message);
        $this->topicStatus->incrementRevision();

        if (count($this->containers) > $this->holdCount) {
            array_shift($this->containers);
            $this->referenceOffset++;
        }
    }

    /**
     * @param int $sinceRevision
     * @return TopicContainer[]
     */
    public function get(int $sinceRevision) : array
    {
        $sinceRevision -= $this->referenceOffset;
        $sinceRevision = $sinceRevision < 0 ? 0 : $sinceRevision;
        return array_slice($this->containers, $sinceRevision);
    }

    /**
     * @return TopicStatus
     */
    public function getTopicStatus(): TopicStatus
    {
        return $this->topicStatus;
    }
}