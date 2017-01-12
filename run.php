<?php
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Volante\SkyBukkit\Monitor\App\OperatorServer;

require __DIR__.'/vendor/autoload.php';

$server = IoServer::factory(new HttpServer(new WsServer(new OperatorServer())), 8080);
$server->run();