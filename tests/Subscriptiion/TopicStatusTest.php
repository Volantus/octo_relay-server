<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Subscription;

use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatus;

/**
 * Class TopicStatusTest
 *
*@package Volante\SkyBukkit\RelayServer\Tests\Subscription
 */
class TopicStatusTest extends \PHPUnit_Framework_TestCase
{
    public function test_jsonSerialize_correct()
    {
        $status = new TopicStatus('testTopic', 5);
        $expected = [
            'name'     => 'testTopic',
            'revision' => 5
        ];
        self::assertEquals($expected, $status->jsonSerialize());
    }
}