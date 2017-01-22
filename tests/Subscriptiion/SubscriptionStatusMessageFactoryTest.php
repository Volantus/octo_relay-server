<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Subscription;

use Volante\SkyBukkit\Common\Src\Server\Messaging\IncomingMessage;
use Volante\SkyBukkit\Common\Src\Server\Network\NetworkRawMessage;
use Volante\SkyBukkit\Common\Tests\Server\General\MessageFactoryTestCase;
use Volante\SkyBukkit\RelayServer\Src\Subscription\SubscriptionStatusMessage;
use Volante\SkyBukkit\RelayServer\Src\Subscription\SubscriptionStatusMessageFactory;
use Volante\SkyBukkit\RelayServer\Src\Subscription\TopicStatus;

/**
 * Class SubscriptionStatusMessageFactoryTest
 *
 * @package Volante\SkyBukkit\RelayServer\Tests\Subscription
 */
class SubscriptionStatusMessageFactoryTest extends MessageFactoryTestCase
{
    /**
     * @var SubscriptionStatusMessageFactory
     */
    private $factory;

    protected function setUp()
    {
        parent::setUp();
        $this->factory = new SubscriptionStatusMessageFactory();
    }

    public function test_getType_correct()
    {
        self::assertEquals(SubscriptionStatusMessage::TYPE, $this->factory->getType());
    }

    public function test_create_statusKeyMissing()
    {
        $this->validateMissingKey('status');
    }

    public function test_create_statusNotArray()
    {
        $this->validateNotArray('status');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid subscriptionStatusMessage message: name key is missing
     */
    public function test_create_statusTopicMissing()
    {
        $data = $this->getCorrectMessageData();
        unset($data['status'][0]['name']);
        $message = $this->getRawMessage($data);
        $this->callFactory($message);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid subscriptionStatusMessage message: revision key is missing
     */
    public function test_create_statusRevisionMissing()
    {
        $data = $this->getCorrectMessageData();
        unset($data['status'][0]['revision']);
        $message = $this->getRawMessage($data);
        $this->callFactory($message);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid subscriptionStatusMessage message: value of key name is not a string
     */
    public function test_create_statusTopicNotString()
    {
        $data = $this->getCorrectMessageData();
        $data['status'][0]['name'] = [];
        $message = $this->getRawMessage($data);
        $this->callFactory($message);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid subscriptionStatusMessage message: value of key revision is not numeric
     */
    public function test_create_statusRevisionNotNumeric()
    {
        $data = $this->getCorrectMessageData();
        $data['status'][0]['revision'] = 'abc';
        $message = $this->getRawMessage($data);
        $this->callFactory($message);
    }

    public function test_create_correct()
    {
        $data = $this->getCorrectMessageData();
        $message = $this->getRawMessage($data);
        /** @var SubscriptionStatusMessage $result */
        $result = $this->callFactory($message);

        self::assertInstanceOf(SubscriptionStatusMessage::class, $result);
        self::assertSame($this->client, $result->getSender());
        self::assertCount(2, $result->getStatus());
        self::assertInstanceOf(TopicStatus::class, $result->getStatus()[0]);
        self::assertInstanceOf(TopicStatus::class, $result->getStatus()[1]);
        self::assertEquals(new TopicStatus('topic1', 16), $result->getStatus()[0]);
        self::assertEquals(new TopicStatus('topic2', 3), $result->getStatus()[1]);
    }

    /**
     * @return string
     */
    protected function getMessageType(): string
    {
        return SubscriptionStatusMessage::TYPE;
    }

    /**
     * @param NetworkRawMessage $rawMessage
     *
     * @return mixed
     */
    protected function callFactory(NetworkRawMessage $rawMessage): IncomingMessage
    {
        return $this->factory->create($rawMessage);
    }

    /**
     * @return array
     */
    protected function getCorrectMessageData(): array
    {
        return [
            'status' => [
                ['name' => 'topic1', 'revision' => 16],
                ['name' => 'topic2', 'revision' => 3]
            ]
        ];
    }
}