<?php
namespace Volante\SkyBukkit\RelayServer\Tests\GyroStatus;

use Volante\SkyBukkit\Common\Src\Client\OutgoingMessage;
use Volante\SkyBukkit\Common\Src\General\GyroStatus\GyroStatus;
use Volante\SkyBukkit\RelayServer\Src\GyroStatus\GyroStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicRepository;
use Volante\SkyBukkit\RelayServer\Tests\Topic\TopicRepositoryTestCase;

/**
 * Class GyroStatusRepositoryTest
 * @package Volante\SkyBukkit\RelayServer\Tests\GyroStatus
 */
class GyroStatusRepositoryTest extends TopicRepositoryTestCase
{
    /**
     * @param int $holdCount
     * @return TopicRepository
     */
    protected function createRepository(int $holdCount): TopicRepository
    {
        return new GyroStatusRepository($holdCount);
    }

    /**
     * @return string
     */
    protected function getTopicName(): string
    {
        return GyroStatusRepository::TOPIC;
    }

    /**
     * @return OutgoingMessage
     */
    protected function createOutgoingMessage(): OutgoingMessage
    {
        return new GyroStatus(1, 2, 3);
    }
}