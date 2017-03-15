<?php
namespace Volante\SkyBukkit\RelayServer\Src\FlightController;

use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicRepository;

/**
 * Class PidTuningStatusRepository
 *
 * @package Volante\SkyBukkit\RelayServer\Src\FlightController
 */
class PidTuningStatusRepository extends TopicRepository
{
    const TOPIC = 'pidTuningStatus';

    /**
     * PidFrequencyStatusRepository constructor.
     *
     * @param null $holdCount
     */
    public function __construct($holdCount = null)
    {
        parent::__construct($holdCount ?: getenv('PID_TUNING_STATUS_HOLD_COUNT'), self::TOPIC);
    }
}