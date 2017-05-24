<?php
namespace Volantus\RelayServer\Tests\FlightController;

use Volantus\FlightBase\Src\Client\OutgoingMessage;
use Volantus\FlightBase\Src\General\FlightController\PIDTuningStatus;
use Volantus\FlightBase\Src\General\FlightController\PIDTuningStatusCollection;
use Volantus\RelayServer\Src\FlightController\PidTuningStatusRepository;
use Volantus\RelayServer\Src\Subscription\TopicRepository;
use Volantus\RelayServer\Tests\Topic\TopicRepositoryTestCase;

/**
 * Class PidTuningStatusRepositoryTest
 *
 * @package Volantus\RelayServer\Tests\FlightController
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