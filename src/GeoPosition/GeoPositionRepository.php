<?php
namespace Volantus\RelayServer\Src\GeoPosition;

use Volantus\RelayServer\Src\Subscription\TopicRepository;

/**
 * Class GeoPositionRepository
 * @package Volantus\RelayServer\Src\GeoPosition
 */
class GeoPositionRepository extends TopicRepository
{
    const TOPIC = 'geoPosition';

    /**
     * GeoPositionRepository constructor.
     * @param int $holdCount
     */
    public function __construct($holdCount = null)
    {
        parent::__construct($holdCount ?: getenv('GEO_POSITION_HOLD_COUNT'), self::TOPIC);
    }
}