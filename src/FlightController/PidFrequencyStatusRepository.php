<?php
namespace Volante\SkyBukkit\RelayServer\Src\FlightController;

use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicRepository;

/**
 * Class PidFrequencyStatusRepository
 *
 * @package Volante\SkyBukkit\RelayServer\Src\FlightController
 */
class PidFrequencyStatusRepository extends TopicRepository
{
    const TOPIC = 'pidFrequencyStatus';

    /**
     * PidFrequencyStatusRepository constructor.
     *
     * @param null $holdCount
     */
    public function __construct($holdCount = null)
    {
        parent::__construct($holdCount ?: getenv('PID_FREQUENCY_STATUS_HOLD_COUNT'), self::TOPIC);
    }
}