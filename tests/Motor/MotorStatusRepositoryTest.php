<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Motor;

use Volante\SkyBukkit\Common\Src\Client\OutgoingMessage;
use Volante\SkyBukkit\Common\Src\General\Motor\Motor;
use Volante\SkyBukkit\Common\Src\General\Motor\MotorStatus;
use Volante\SkyBukkit\RelayServer\Src\Motor\MotorStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicRepository;
use Volante\SkyBukkit\RelayServer\Tests\Topic\TopicRepositoryTestCase;

/**
 * Class MotorStatusRepositoryTest
 *
 * @package Volante\SkyBukkit\RelayServer\Tests\Motor
 */
class MotorStatusRepositoryTest extends TopicRepositoryTestCase
{
    /**
     * @param int $holdCount
     *
     * @return TopicRepository
     */
    protected function createRepository(int $holdCount): TopicRepository
    {
        return new MotorStatusRepository($holdCount);
    }

    /**
     * @return string
     */
    protected function getTopicName(): string
    {
        return MotorStatusRepository::TOPIC;
    }

    /**
     * @return OutgoingMessage
     */
    protected function createOutgoingMessage(): OutgoingMessage
    {
        return new MotorStatus([
            new Motor(1, Motor::ZERO_LEVEL, 22),
            new Motor(2, Motor::ZERO_LEVEL, 25)
        ]);
    }
}