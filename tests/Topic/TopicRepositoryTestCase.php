<?php
namespace Volantus\RelayServer\Tests\Topic;

use Volantus\FlightBase\Src\Client\OutgoingMessage;
use Volantus\RelayServer\Src\Subscription\TopicContainer;
use Volantus\RelayServer\Src\Subscription\TopicRepository;
use Volantus\RelayServer\Src\Subscription\TopicStatus;

/**
 * Class TopicRepositoryTestCase
 * @package Volantus\RleayServer\Tests\Topic
 */
abstract class TopicRepositoryTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TopicRepository
     */
    private $repository;

    protected function setUp()
    {
        $this->repository = $this->createRepository(10);
    }

    /**
     * @param int $holdCount
     * @return TopicRepository
     */
    abstract protected function createRepository(int $holdCount) : TopicRepository;

    /**
     * @return string
     */
    abstract protected function getTopicName() : string;

    /**
     * @return OutgoingMessage
     */
    abstract protected function createOutgoingMessage() : OutgoingMessage;

    public function test_getTopicStatus_nameCorrect()
    {
        $topicStatus = $this->repository->getTopicStatus();

        self::assertInstanceOf(TopicStatus::class, $topicStatus);
        self::assertEquals($this->getTopicName(), $topicStatus->getName());
    }

    public function test_add_statusIncremented()
    {
        $revisionBefore = $this->repository->getTopicStatus()->getRevision();
        $this->repository->add($this->createOutgoingMessage());
        $revisionAfter = $this->repository->getTopicStatus()->getRevision();

        self::assertEquals(0, $revisionBefore);
        self::assertEquals(1, $revisionAfter);
    }

    public function test_get_correctOffset()
    {
        $expected = [
            $this->createOutgoingMessage(),
            $this->createOutgoingMessage(),
            $this->createOutgoingMessage()
        ];

        $this->repository->add($this->createOutgoingMessage());
        $this->repository->add($expected[0]);
        $this->repository->add($expected[1]);
        $this->repository->add($expected[2]);

        $result = $this->repository->get(1);
        self::assertCount(3, $result);
        self::assertInstanceOf(TopicContainer::class, $result[0]);
        self::assertInstanceOf(TopicContainer::class, $result[1]);
        self::assertInstanceOf(TopicContainer::class, $result[2]);
        self::assertSame($expected[0], $result[0]->getPayload());
        self::assertSame($expected[1], $result[1]->getPayload());
        self::assertSame($expected[2], $result[2]->getPayload());
    }

    public function test_get_correctTopic()
    {
        $expected = [
            $this->createOutgoingMessage(),
            $this->createOutgoingMessage()
        ];
        $this->repository->add($expected[0]);
        $this->repository->add($expected[1]);

        $result = $this->repository->get(0);
        self::assertCount(2, $result);
        self::assertInstanceOf(TopicContainer::class, $result[0]);
        self::assertInstanceOf(TopicContainer::class, $result[1]);
        self::assertSame($expected[0], $result[0]->getPayload());
        self::assertSame($expected[1], $result[1]->getPayload());
        self::assertEquals($this->getTopicName(), $result[0]->getTopic()->getName());
        self::assertEquals($this->getTopicName(), $result[1]->getTopic()->getName());
        self::assertEquals(0, $result[0]->getTopic()->getRevision());
        self::assertEquals(1, $result[1]->getTopic()->getRevision());
    }

    public function test_get_revisionTooHigh()
    {
        $expected = [
            $this->createOutgoingMessage(),
            $this->createOutgoingMessage()
        ];
        $this->repository->add($expected[0]);
        $this->repository->add($expected[1]);
        self::assertEquals([], $this->repository->get(10));
    }

    public function test_get_holdCountRespected()
    {
        for($i = 0; $i < 20; $i++) {
            $this->repository->add($this->createOutgoingMessage());
        }

        $result = $this->repository->get(0);
        self::assertCount(10, $result);
        self::assertEquals(10, $result[0]->getTopic()->getRevision());
    }
}