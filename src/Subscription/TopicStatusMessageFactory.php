<?php
namespace Volante\SkyBukkit\RelayServer\Src\Subscription;

use Volante\SkyBukkit\RelayServer\Src\GeoPosition\GeoPositionRepository;
use Volante\SkyBukkit\RelayServer\Src\GyroStatus\GyroStatusRepository;

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
     * @var GyroStatusRepository
     */
    private $gyroStatusRepository;

    /**
     * TopicStatusMessageFactory constructor.
     *
     * @param GeoPositionRepository $geoPositionRepository
     * @param GyroStatusRepository  $gyroStatusRepository
     */
    public function __construct(GeoPositionRepository $geoPositionRepository, GyroStatusRepository $gyroStatusRepository)
    {
        $this->geoPositionRepository = $geoPositionRepository;
        $this->gyroStatusRepository = $gyroStatusRepository;
    }

    /**
     * @return TopicStatusMessage
     */
    public function getMessage() : TopicStatusMessage
    {
        $status = [
            $this->geoPositionRepository->getTopicStatus(),
            $this->gyroStatusRepository->getTopicStatus()
        ];
        return new TopicStatusMessage($status);
    }
}