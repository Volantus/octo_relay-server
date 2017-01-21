<?php
namespace Volante\SkyBukkit\RelayServer\Src\Subscription;

use Volante\SkyBukkit\RelayServer\Src\GeoPosition\GeoPositionRepository;

/**
 * Class TopicStatusMessageFactory
 * @package Volante\SkyBukkit\RelayServer\Src\Subscription
 */
class TopicStatusMessageFactory
{
    /**
     * @var GeoPositionRepository
     */
    private $geoPositionRepository;

    /**
     * TopicStatusMessageFactory constructor.
     *
     * @param GeoPositionRepository $geoPositionRepository
     */
    public function __construct(GeoPositionRepository $geoPositionRepository)
    {
        $this->geoPositionRepository = $geoPositionRepository;
    }

    /**
     * @return TopicStatusMessage
     */
    public function getMessage() : TopicStatusMessage
    {
        $status = [
            $this->geoPositionRepository->getTopicStatus()
        ];
        return new TopicStatusMessage($status);
    }
}