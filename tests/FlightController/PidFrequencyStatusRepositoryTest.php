<?php
namespace Volantus\RelayServer\Tests\FlightController;

use Volantus\FlightBase\Src\Client\OutgoingMessage;
use Volantus\FlightBase\Src\General\FlightController\PIDFrequencyStatus;
use Volantus\RelayServer\Src\FlightController\PidFrequencyStatusRepository;
use Volantus\RelayServer\Src\Subscription\TopicRepository;
use Volantus\RelayServer\Tests\Topic\TopicRepositoryTestCase;

/**
 * Class PidFrequencyStatusRepositoryTest
 *
 * @package Volantus\RelayServer\Tests\FlightController
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