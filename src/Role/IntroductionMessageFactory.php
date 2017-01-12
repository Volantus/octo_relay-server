<?php
namespace Volante\SkyBukkit\RelayServer\Src\Role;

use Assert\Assertion;
use Volante\SkyBukkit\RelayServer\Src\Network\RawMessage;

/**
 * Class IntroductionMessageFactory
 * @package Volante\SkyBukkit\RelayServer\Src\Role
 */
class IntroductionMessageFactory
{
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
        Assertion::keyExists($data, 'role', 'Invalid introduction message: role key is missing');
        Assertion::numeric($data['role'], 'Invalid introduction message: role is not numeric');
        Assertion::inArray($data['role'], ClientRole::getSupportedRoles(), 'Invalid introduction message: given role is not supported');
    }
}