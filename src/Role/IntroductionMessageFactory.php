<?php
namespace Volante\SkyBukkit\RelayServer\Src\Role;

use Assert\Assertion;
use Volante\SkyBukkit\RelayServer\Src\Messaging\MessageFactory;
use Volante\SkyBukkit\RelayServer\Src\Network\RawMessage;

/**
 * Class IntroductionMessageFactory
 * @package Volante\SkyBukkit\RelayServer\Src\Role
 */
class IntroductionMessageFactory extends MessageFactory
{
    /**
     * @var string
     */
    protected $label = IntroductionMessage::TYPE;

    /**
     * @param RawMessage $rawMessage
     * @return IntroductionMessage
     */
    public function create(RawMessage $rawMessage) : IntroductionMessage
    {
        $this->validate($rawMessage->getData());
        return new IntroductionMessage($rawMessage->getSender(), (int) $rawMessage->getData()['role']);
    }

    /**
     * @param array $data
     */
    protected function validate(array $data)
    {
        $this->validateNumeric($data, 'role');
        Assertion::inArray($data['role'], ClientRole::getSupportedRoles(), 'Invalid ' . IntroductionMessage::TYPE . ' message: given role is not supported');
    }
}