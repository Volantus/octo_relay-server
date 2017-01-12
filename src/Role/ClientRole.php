<?php
namespace Volante\SkyBukkit\RelayServer\Src\Role;

/**
 * Class ClientRole
 * @package Volante\SkyBukkit\RelayServer\Src\Role
 */
abstract class ClientRole
{
    const OPERATOR          = 1;
    const FLIGHT_CONTROLLER = 2;
    const STATUS_BROKER     = 3;

    /**
     * @return array
     */
    public static function getSupportedRoles(): array
    {
        return [self::OPERATOR, self::FLIGHT_CONTROLLER, self::STATUS_BROKER];
    }
}