<?php
namespace Volantus\RelayServer\Src\FlightController;

use Volantus\RelayServer\Src\Subscription\TopicRepository;

/**
 * Class PidFrequencyStatusRepository
 *
 * @package Volantus\RelayServer\Src\FlightController
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