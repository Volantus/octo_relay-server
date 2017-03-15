<?php
namespace Volante\SkyBukkit\RelayServer\Tests\FlightController;

use Volante\SkyBukkit\Common\Src\Client\OutgoingMessage;
use Volante\SkyBukkit\Common\Src\General\FlightController\PIDTuningStatus;
use Volante\SkyBukkit\Common\Src\General\FlightController\PIDTuningStatusCollection;
use Volante\SkyBukkit\RelayServer\Src\FlightController\PidTuningStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicRepository;
use Volante\SkyBukkit\RelayServer\Tests\Topic\TopicRepositoryTestCase;

/**
 * Class PidTuningStatusRepositoryTest
 *
 * @package Volante\SkyBukkit\RelayServer\Tests\FlightController
 */
class PidTuningStatusRepositoryTest extends TopicRepositoryTestCase
{
    /**
     * @param int $holdCount
     *
     * @return TopicRepository
     */
    protected function createRepository(int $holdCount): TopicRepository
    {
        return new PidTuningStatusRepository($holdCount);
    }

    /**
     * @return string
     */
    protected function getTopicName(): string
    {
        return PidTuningStatusRepository::TOPIC;
    }

    /**
     * @return OutgoingMessage
     */
    protected function createOutgoingMessage(): OutgoingMessage
    {
        return new PIDTuningStatusCollection(new PIDTuningStatus(1, 2, 3), new PIDTuningStatus(4, 5, 6), new PIDTuningStatus(7, 8, 9));
    }
}