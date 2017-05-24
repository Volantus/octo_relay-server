<?php
namespace Volantus\RelayServer\Src\FlightController;

use Volantus\RelayServer\Src\Subscription\TopicRepository;

/**
 * Class PidTuningStatusRepository
 *
 * @package Volantus\RelayServer\Src\FlightController
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