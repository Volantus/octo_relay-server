<?php
namespace Volante\SkyBukkit\RleayServer\Tests\GeoPosition;

use Volante\SkyBukkit\Common\Src\Client\OutgoingMessage;
use Volante\SkyBukkit\Common\Src\General\GeoPosition\GeoPosition;
use Volante\SkyBukkit\RelayServer\Src\GeoPosition\GeoPositionRepository;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicRepository;
use Volante\SkyBukkit\RelayServer\Tests\Topic\TopicRepositoryTestCase;


/**
 * Class GeoPositionRepositoryTest
 * @package Volante\SkyBukkit\RleayServer\Tests\GeoPosition
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