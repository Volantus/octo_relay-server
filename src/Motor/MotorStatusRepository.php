<?php
namespace Volante\SkyBukkit\RelayServer\Src\Motor;

use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicRepository;

/**
 * Class MotorStatusRepository
 *
 * @package Volante\SkyBukkit\RelayServer\Src
 */
class MotorStatusRepository extends TopicRepository
{
    const TOPIC = 'motorStatus';

    /**
     * GeoPositionRepository constructor.
     * @param int $holdCount
     */
    public function __construct($holdCount = null)
    {
        parent::__construct($holdCount ?: getenv('MOTOR_STATUS_HOLD_COUNT'), self::TOPIC);
    }
}