<?php
namespace Volantus\RelayServer\Src\Motor;

use Volantus\RelayServer\Src\Subscription\TopicRepository;

/**
 * Class MotorStatusRepository
 *
 * @package Volantus\RelayServer\Src
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