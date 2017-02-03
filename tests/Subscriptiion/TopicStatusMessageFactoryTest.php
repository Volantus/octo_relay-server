<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Subscription;

use Volante\SkyBukkit\RelayServer\Src\GeoPosition\GeoPositionRepository;
use Volante\SkyBukkit\RelayServer\Src\GyroStatus\GyroStatusRepository;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatus;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatusMessageFactory;

/**
 * Class TopicStatusMessageFactoryTest
 *
 * @package Volante\SkyBukkit\RelayServer\Tests\Subscription
 */
class TopicStatusMessageFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TopicStatusMessageFactory
     */
    private $factory;

    /**
     * @var GeoPositionRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $geoRepository;

    /**
     * @var GyroStatusRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $gyroRepository;

    protected function setUp()
    {
        $this->geoRepository = $this->getMockBuilder(GeoPositionRepository::class)->disableOriginalConstructor()->getMock();
        $this->gyroRepository = $this->getMockBuilder(GyroStatusRepository::class)->disableOriginalConstructor()->getMock();
        $this->factory = new TopicStatusMessageFactory($this->geoRepository, $this->gyroRepository);
    }

    public function test_getMessage_repositoryCalled()
    {
        $geoTopicStatus = new TopicStatus(GeoPositionRepository::TOPIC, 5);
        $this->geoRepository->expects(self::once())
            ->method('getTopicStatus')
            ->willReturn($geoTopicStatus);

        $gyroTopicStatus = new TopicStatus(GyroStatusRepository::TOPIC, 10);
        $this->gyroRepository->expects(self::once())
            ->method('getTopicStatus')
            ->willReturn($gyroTopicStatus);

        $result = $this->factory->getMessage();
        self::assertInstanceOf(TopicStatusMessage::class, $result);
        self::assertEquals([$geoTopicStatus, $gyroTopicStatus], $result->getStatus());
    }
}