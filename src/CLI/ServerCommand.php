<?php
namespace Volante\SkyBukkit\RelayServer\Src\CLI;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Volante\SkyBukkit\RelayServer\Src\Controller;
use Volante\SkyBukkit\RelayServer\Src\Messaging\MessageRelayService;

/**
 * Class ServerCommand
 * @package Volante\SkyBukkit\RelayServer\Src\CLI
 */
class ServerCommand extends Command
{
    protected function configure()
    {
        $this->setName('server');
        $this->setDescription('Runs the relay server');

        $this->addOption('port', 'p', InputArgument::OPTIONAL, 'Port of the webSocket', 8080);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = new MessageRelayService($output);
        $controller = new Controller($output, $service);

        $server = IoServer::factory(new HttpServer(new WsServer($controller)), $input->getOption('port'));
        $server->run();
    }
}