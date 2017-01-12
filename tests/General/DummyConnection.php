<?php
namespace Volante\SkyBukkit\RelayServer\Tests\General;

use Ratchet\ConnectionInterface;

/**
 * Class DummyConnection
 * @package Volante\SkyBukkit\RelayServer\Tests\General
 */
class DummyConnection implements ConnectionInterface
{
    /**
     * @inheritdoc
     */
    function send($data)
    {
    }

    /**
     * @inheritdoc
     */
    function close()
    {
    }
}