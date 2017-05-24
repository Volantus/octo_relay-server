<?php
namespace Volantus\RelayServer\Src;

use Symfony\Component\Console\Output\OutputInterface;
use Volantus\RelayServer\Src\Messaging\MessageRelayService;

/**
 * Class OperatorServer
 * @package Volantus\Monitor\App
 */
class Controller extends \Volantus\FlightBase\Src\Server\Controller
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