<?php
namespace Volantus\RelayServer\Tests\Motor;

use Volantus\FlightBase\Src\Client\OutgoingMessage;
use Volantus\FlightBase\Src\General\Motor\Motor;
use Volantus\FlightBase\Src\General\Motor\MotorStatus;
use Volantus\RelayServer\Src\Motor\MotorStatusRepository;
use Volantus\RelayServer\Src\Subscription\TopicRepository;
use Volantus\RelayServer\Tests\Topic\TopicRepositoryTestCase;

/**
 * Class MotorStatusRepositoryTest
 *
 * @package Volantus\RelayServer\Tests\Motor
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