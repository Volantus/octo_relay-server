<?php
namespace Volante\SkyBukkit\RelayServer\Src;

use Volante\SkyBukkit\RelayServer\Src\Network\Client;
use Volante\SkyBukkit\RelayServer\Src\Network\Message;
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
     * MessageService constructor.
     * @param RawMessageFactory $rawMessageFactory
     * @param IntroductionMessageFactory $introductionMessageFactory
     */
    public function __construct(RawMessageFactory $rawMessageFactory = null, IntroductionMessageFactory $introductionMessageFactory = null)
    {
        $this->rawMessageFactory = $rawMessageFactory ?: new RawMessageFactory();
        $this->introductionMessageFactory = $introductionMessageFactory ?: new IntroductionMessageFactory();
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
            default:
                throw new \InvalidArgumentException('Unable to handle message: given type <' . $rawMessage->getType() . '> is unknown');
        }
    }
}