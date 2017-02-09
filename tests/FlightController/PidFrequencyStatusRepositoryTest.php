<?php
namespace Volante\SkyBukkit\RelayServer\Tests\FlightController;

use Volante\SkyBukkit\Common\Src\Client\OutgoingMessage;
use Volante\SkyBukkit\Common\Src\General\FlightController\PIDFrequencyStatus;
use Volante\SkyBukkit\RelayServer\Src\FlightController\PidFrequencyStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicRepository;
use Volante\SkyBukkit\RelayServer\Tests\Topic\TopicRepositoryTestCase;

/**
 * Class PidFrequencyStatusRepositoryTest
 *
 * @package Volante\SkyBukkit\RelayServer\Tests\FlightController
 */
class PidFrequencyStatusRepositoryTest extends TopicRepositoryTestCase
{
    /**
     * @param int $holdCount
     *
     * @return TopicRepository
     */
    protected function createRepository(int $holdCount): TopicRepository
    {
        return new PidFrequencyStatusRepository($holdCount);
    }

    /**
     * @return string
     */
    protected function getTopicName(): string
    {
        return PidFrequencyStatusRepository::TOPIC;
    }

    /**
     * @return OutgoingMessage
     */
    protected function createOutgoingMessage(): OutgoingMessage
    {
        return new PIDFrequencyStatus(100, 980);
    }
}