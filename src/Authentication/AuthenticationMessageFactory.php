<?php
namespace Volante\SkyBukkit\RelayServer\Src\Authentication;

use Volante\SkyBukkit\RelayServer\Src\Messaging\MessageFactory;
use Volante\SkyBukkit\RelayServer\Src\Network\RawMessage;

/**
 * Class AuthenticationMessageFactory
 * @package Volante\SkyBukkit\RelayServer\Src\Authentication
 */
class AuthenticationMessageFactory extends MessageFactory
{
    /**
     * @var string
     */
    protected $label = AuthenticationMessage::LABEL;

    /**
     * @param RawMessage $rawMessage
     * @return AuthenticationMessage
     */
    public function create(RawMessage $rawMessage) : AuthenticationMessage
    {
        $this->validateString($rawMessage->getData(), 'token');
        return new AuthenticationMessage($rawMessage->getSender(), $rawMessage->getData()['token']);
    }
}