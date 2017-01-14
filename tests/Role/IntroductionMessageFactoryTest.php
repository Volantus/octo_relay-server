<?php
namespace Volante\SkyBukkit\RleayServer\Tests\Role;

use Volante\SkyBukkit\RelayServer\Src\Messaging\Message;
use Volante\SkyBukkit\RelayServer\Src\Network\RawMessage;
use Volante\SkyBukkit\Common\Src\General\Role\ClientRole;
use Volante\SkyBukkit\RelayServer\Src\Role\IntroductionMessage;
use Volante\SkyBukkit\RelayServer\Src\Role\IntroductionMessageFactory;
use Volante\SkyBukkit\RelayServer\Tests\General\MessageFactoryTestCase;

/**
 * Class IntroductionMessageFactoryTest
 * @package Volante\SkyBukkit\RleayServer\Tests\Role
 */
class IntroductionMessageFactoryTest extends MessageFactoryTestCase
{
    /**
     * @var IntroductionMessageFactory
     */
    private $factory;

    protected function setUp()
    {
        parent::setUp();
        $this->factory = new IntroductionMessageFactory();
    }

    public function test_create_roleKeyMissing()
    {
        $this->validateMissingKey('role');
    }

    public function test_create_roleNotNumeric()
    {
        $this->validateNotNumeric('role');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid introduction message: given role is not supported
     */
    public function test_create_roleNotSupported()
    {
        $rawMessage = $this->getRawMessage(['role' => 99]);
        $this->factory->create($rawMessage);
    }

    public function test_create_messageCorrect()
    {
        $rawMessage = $this->getRawMessage(['role' => ClientRole::OPERATOR]);
        $message = $this->factory->create($rawMessage);

        self::assertInstanceOf(IntroductionMessage::class, $message);
        self::assertSame($this->client, $message->getSender());
        self::assertEquals(ClientRole::OPERATOR, $message->getRole());
    }

    /**
     * @return string
     */
    protected function getMessageType(): string
    {
        return IntroductionMessage::TYPE;
    }

    /**
     * @param RawMessage $rawMessage
     * @return mixed
     */
    protected function callFactory(RawMessage $rawMessage) : Message
    {
        return $this->factory->create($rawMessage);
    }

    /**
     * @return array
     */
    protected function getCorrectMessageData(): array
    {
       return ['role' => ClientRole::OPERATOR];
    }
}