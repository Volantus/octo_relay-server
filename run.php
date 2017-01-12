<?php
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Volante\SkyBukkit\RelayServer\Src\Controller;

require __DIR__.'/vendor/autoload.php';

$server = IoServer::factory(new HttpServer(new WsServer(new Controller())), 8080);
$server->run();