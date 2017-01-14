<?php
namespace Volante\SkyBukkit\RelayServer\Src\Authentication;

use Guzzle\Common\Exception\RuntimeException;

/**
 * Class UnauthorizedException
 * @package Volante\SkyBukkit\RelayServer\Src\Authentication
 */
class UnauthorizedException extends RuntimeException
{
    /**
     * UnauthorizedException constructor.
     * @param string $message
     */
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}