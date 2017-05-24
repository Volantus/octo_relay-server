<?php
namespace Volantus\RelayServer\Src\GyroStatus;

use Volantus\RelayServer\Src\Subscription\TopicRepository;

/**
 * Class GyroStatusRepository
 * @package Volantus\RelayServer\Src\GyroStatus
 */
class GyroStatusRepository extends TopicRepository
{
    const TOPIC = 'gyroStatus';

    /**
     * GeoPositionRepository constructor.
     * @param int $holdCount
     */
    public function __construct($holdCount = null)
    {
        parent::__construct($holdCount ?: getenv('GYRO_STATUS_HOLD_COUNT'), self::TOPIC);
    }
}