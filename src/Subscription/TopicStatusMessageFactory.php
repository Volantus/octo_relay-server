<?php
namespace Volante\SkyBukkit\RelayServer\Src\Subscription;

use Volante\SkyBukkit\RelayServer\Src\FlightController\PidFrequencyStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\FlightController\PidTuningStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\GeoPosition\GeoPositionRepository;
use Volante\SkyBukkit\RelayServer\Src\GyroStatus\GyroStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\Motor\MotorStatusRepository;

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
     * @var MotorStatusRepository
     */
    private $motorStatusRepository;

    /**
     * @var PidFrequencyStatusRepository
     */
    private $pidFrequencyStatusRepository;

    /**
     * @var PidTuningStatusRepository
     */
    private $pidTuningStatusRepository;

    /**
     * TopicStatusMessageFactory constructor.
     *
     * @param GeoPositionRepository        $geoPositionRepository
     * @param GyroStatusRepository         $gyroStatusRepository
     * @param MotorStatusRepository        $motorStatusRepository
     * @param PidFrequencyStatusRepository $pidFrequencyStatusRepository
     * @param PidTuningStatusRepository    $pidTuningStatusRepository
     */
    public function __construct(GeoPositionRepository $geoPositionRepository, GyroStatusRepository $gyroStatusRepository, MotorStatusRepository $motorStatusRepository, PidFrequencyStatusRepository $pidFrequencyStatusRepository, PidTuningStatusRepository $pidTuningStatusRepository)
    {
        $this->geoPositionRepository = $geoPositionRepository;
        $this->gyroStatusRepository = $gyroStatusRepository;
        $this->motorStatusRepository = $motorStatusRepository;
        $this->pidFrequencyStatusRepository = $pidFrequencyStatusRepository;
        $this->pidTuningStatusRepository = $pidTuningStatusRepository;
    }

    /**
     * @return TopicStatusMessage
     */
    public function getMessage() : TopicStatusMessage
    {
        $status = [
            $this->geoPositionRepository->getTopicStatus(),
            $this->gyroStatusRepository->getTopicStatus(),
            $this->motorStatusRepository->getTopicStatus(),
            $this->pidFrequencyStatusRepository->getTopicStatus(),
            $this->pidTuningStatusRepository->getTopicStatus()
        ];
        return new TopicStatusMessage($status);
    }
}