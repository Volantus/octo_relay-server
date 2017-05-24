<?php
namespace Volantus\RelayServer\Tests\GyroStatus;

use Volantus\FlightBase\Src\Client\OutgoingMessage;
use Volantus\FlightBase\Src\General\GyroStatus\GyroStatus;
use Volantus\RelayServer\Src\GyroStatus\GyroStatusRepository;
use Volantus\RelayServer\Src\Subscription\TopicRepository;
use Volantus\RelayServer\Tests\Topic\TopicRepositoryTestCase;

/**
 * Class GyroStatusRepositoryTest
 * @package Volantus\RelayServer\Tests\GyroStatus
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