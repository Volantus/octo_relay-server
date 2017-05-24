<?php
namespace Volantus\RelayServer\Tests\Subscription;

use Volantus\RelayServer\Src\FlightController\PidFrequencyStatusRepository;
use Volantus\RelayServer\Src\FlightController\PidTuningStatusRepository;
use Volantus\RelayServer\Src\GeoPosition\GeoPositionRepository;
use Volantus\RelayServer\Src\GyroStatus\GyroStatusRepository;
use Volantus\RelayServer\Src\Motor\MotorStatusRepository;
use Volantus\RelayServer\Src\Subscription\TopicStatus;
use Volantus\RelayServer\Src\Subscription\TopicStatusMessage;
use Volantus\RelayServer\Src\Subscription\TopicStatusMessageFactory;

/**
 * Class TopicStatusMessageFactoryTest
 *
 * @package Volantus\RelayServer\Tests\Subscription
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

    /**
     * @var MotorStatusRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $motorRepository;

    /**
     * @var PidFrequencyStatusRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $frequencyRepository;

    /**
     * @var PidTuningStatusRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $tuningStatusRepository;

    protected function setUp()
    {
        $this->geoRepository = $this->getMockBuilder(GeoPositionRepository::class)->disableOriginalConstructor()->getMock();
        $this->gyroRepository = $this->getMockBuilder(GyroStatusRepository::class)->disableOriginalConstructor()->getMock();
        $this->motorRepository = $this->getMockBuilder(MotorStatusRepository::class)->disableOriginalConstructor()->getMock();
        $this->frequencyRepository = $this->getMockBuilder(PidFrequencyStatusRepository::class)->disableOriginalConstructor()->getMock();
        $this->tuningStatusRepository = $this->getMockBuilder(PidTuningStatusRepository::class)->disableOriginalConstructor()->getMock();
        $this->factory = new TopicStatusMessageFactory($this->geoRepository, $this->gyroRepository, $this->motorRepository, $this->frequencyRepository, $this->tuningStatusRepository);
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

        $motorTopicStatus = new TopicStatus(MotorStatusRepository::TOPIC, 10);
        $this->motorRepository->expects(self::once())
            ->method('getTopicStatus')
            ->willReturn($motorTopicStatus);

        $frequencyTopicStatus = new TopicStatus(PidFrequencyStatusRepository::TOPIC, 10);
        $this->frequencyRepository->expects(self::once())
            ->method('getTopicStatus')
            ->willReturn($frequencyTopicStatus);

        $pidTuningStatus = new TopicStatus(PidTuningStatusRepository::TOPIC, 10);
        $this->tuningStatusRepository->expects(self::once())
            ->method('getTopicStatus')
            ->willReturn($pidTuningStatus);

        $result = $this->factory->getMessage();
        self::assertInstanceOf(TopicStatusMessage::class, $result);
        self::assertEquals([$geoTopicStatus, $gyroTopicStatus, $motorTopicStatus, $frequencyTopicStatus, $pidTuningStatus], $result->getStatus());
    }
}