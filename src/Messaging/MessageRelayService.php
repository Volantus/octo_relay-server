<?php
namespace Volante\SkyBukkit\RelayServer\Src\Messaging;

use Symfony\Component\Console\Output\OutputInterface;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageServerService;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageService;
use Volante\SkyBukkit\RelayServer\Src\Network\ClientFactory;

/**
 * Class MessageRelayService
 * @package Volante\SkyBukkit\RelayServer\Src\Messaging
 */
class MessageRelayService extends MessageServerService
{
    public function __construct(OutputInterface $output, MessageService $messageService = null, ClientFactory $clientFactory = null)
    {
        parent::__construct($output, $messageService, $clientFactory ?: new ClientFactory());
    }
}