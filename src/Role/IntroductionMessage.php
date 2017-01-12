<?php
namespace Volante\SkyBukkit\RelayServer\Src\Role;

use Volante\SkyBukkit\RelayServer\Src\Network\Message;
use Volante\SkyBukkit\RelayServer\Src\Network\Client;

/**
 * Class IntroduceMessage
 * @package Volante\SkyBukkit\RelayServer\Src\Role
 */
class IntroductionMessage extends Message
{
    const TYPE = 'introduction';

    /**
     * @var int
     */
    private $role;

    /**
     * IntroductionMessage constructor.
     * @param Client $sender
     * @param int $role
     */
    public function __construct(Client $sender, int $role)
    {
        parent::__construct($sender);
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }
}