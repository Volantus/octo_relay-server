<?php
namespace Volante\SkyBukkit\RelayServer\Src;

use Symfony\Component\Console\Output\OutputInterface;
use Volante\SkyBukkit\RelayServer\Src\Messaging\MessageRelayService;

/**
 * Class OperatorServer
 * @package Volante\SkyBukkit\Monitor\App
 */
class Controller extends \Volante\SkyBukkit\Common\Src\Server\Controller
{
    /**
     * Controller constructor.
     * @param OutputInterface $output
     * @param MessageRelayService $messageRelayService
     */
    public function __construct(OutputInterface $output, MessageRelayService $messageRelayService)
    {
        parent::__construct($output, $messageRelayService);
    }
}