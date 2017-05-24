<?php
namespace Volantus\RleayServer\Tests\GeoPosition;

use Volantus\FlightBase\Src\Client\OutgoingMessage;
use Volantus\FlightBase\Src\General\GeoPosition\GeoPosition;
use Volantus\RelayServer\Src\GeoPosition\GeoPositionRepository;
use Volantus\RelayServer\Src\Subscription\TopicRepository;
use Volantus\RelayServer\Tests\Topic\TopicRepositoryTestCase;


/**
 * Class GeoPositionRepositoryTest
 * @package Volantus\RleayServer\Tests\GeoPosition
 */
class GeoPositionRepositoryTest extends TopicRepositoryTestCase
{
    /**
     * @param int $holdCount
     * @return TopicRepository
     */
    protected function createRepository(int $holdCount): TopicRepository
    {
        return new GeoPositionRepository($holdCount);
    }

    /**
     * @return string
     */
    protected function getTopicName(): string
    {
        return GeoPositionRepository::TOPIC;
    }

    /**
     * @return OutgoingMessage
     */
    protected function createOutgoingMessage(): OutgoingMessage
    {
        return new GeoPosition(1, 2, 3);
    }
}