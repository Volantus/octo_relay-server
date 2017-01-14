<?php
namespace Volante\SkyBukkit\RelayServer\Src\Messaging;

use Volante\SkyBukkit\RelayServer\Src\Authentication\AuthenticationMessage;
use Volante\SkyBukkit\RelayServer\Src\Authentication\AuthenticationMessageFactory;
use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Network\RawMessageFactory;
use Volante\SkyBukkit\RelayServer\Src\Role\IntroductionMessage;
use Volante\SkyBukkit\RelayServer\Src\Role\IntroductionMessageFactory;

/**
 * Class MessageService
 * @package Volante\SkyBukkit\RelayServer\Src
 */
class MessageService
{
    /**
     * @var RawMessageFactory
     */
    private $rawMessageFactory;

    /**
     * @var IntroductionMessageFactory
     */
    private $introductionMessageFactory;

    /**
     * @var AuthenticationMessageFactory
     */
    private $authenticationMessageFactory;

    /**
     * MessageService constructor.
     * @param RawMessageFactory $rawMessageFactory
     * @param IntroductionMessageFactory $introductionMessageFactory
     * @param AuthenticationMessageFactory $authenticationMessageFactory
     */
    public function __construct(RawMessageFactory $rawMessageFactory = null, IntroductionMessageFactory $introductionMessageFactory = null, AuthenticationMessageFactory $authenticationMessageFactory = null)
    {
        $this->rawMessageFactory = $rawMessageFactory ?: new RawMessageFactory();
        $this->introductionMessageFactory = $introductionMessageFactory ?: new IntroductionMessageFactory();
        $this->authenticationMessageFactory = $authenticationMessageFactory ?: new AuthenticationMessageFactory();
    }

    /**
     * @param Client $sender
     * @param string $message
     * @return Message
     */
    public function handle(Client $sender, string $message) : Message
    {
        $rawMessage = $this->rawMessageFactory->create($sender, $message);

        switch ($rawMessage->getType()) {
            case IntroductionMessage::TYPE:
                return $this->introductionMessageFactory->create($rawMessage);
            case AuthenticationMessage::TYPE:
                return $this->authenticationMessageFactory->create($rawMessage);
            default:
                throw new \InvalidArgumentException('Unable to handle message: given type <' . $rawMessage->getType() . '> is unknown');
        }
    }
}