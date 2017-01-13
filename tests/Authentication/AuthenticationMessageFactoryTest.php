<?php
namespace Volante\SkyBukkit\RelayServer\Tests\Authentication;
use Volante\SkyBukkit\RelayServer\Src\Authentication\AuthenticationMessage;
use Volante\SkyBukkit\RelayServer\Src\Authentication\AuthenticationMessageFactory;
use Volante\SkyBukkit\RelayServer\Src\Messaging\Message;
use Volante\SkyBukkit\RelayServer\Src\Network\RawMessage;
use Volante\SkyBukkit\RelayServer\Tests\General\MessageFactoryTestCase;

/**
 * Class AuthenticationMessageFactoryTest
 * @package Volante\SkyBukkit\RelayServer\Tests\Authentication
 */
class AuthenticationMessageFactoryTest extends MessageFactoryTestCase
{
    /**
     * @var AuthenticationMessageFactory
     */
    private $factory;

    protected function setUp()
    {
        parent::setUp();
        $this->factory = new AuthenticationMessageFactory();
    }

    public function test_create_tokenMissing()
    {
        $this->validateMissingKey('token');
    }

    public function test_create_tokenNoString()
    {
        $this->validateNotString('token');
    }

    public function test_create_messageCorrect()
    {
        $message = $this->getRawMessage($this->getCorrectMessageData());
        $result = $this->factory->create($message);

        self::assertInstanceOf(AuthenticationMessage::class, $result);
        self::assertEquals('correctToken', $result->getToken());
    }

    /**
     * @return string
     */
    protected function getMessageType(): string
    {
        return AuthenticationMessage::LABEL;
    }

    /**
     * @param RawMessage $rawMessage
     * @return mixed
     */
    protected function callFactory(RawMessage $rawMessage): Message
    {
        return $this->factory->create($rawMessage);
    }

    /**
     * @return array
     */
    protected function getCorrectMessageData(): array
    {
        return ['token' => 'correctToken'];
    }
}