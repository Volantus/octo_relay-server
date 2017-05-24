<?php
use Dotenv\Dotenv;
use Symfony\Component\Console\Application;
use Volantus\RelayServer\Src\CLI\ServerCommand;

require __DIR__.'/vendor/autoload.php';

$dotEnv = new Dotenv(__DIR__);
$dotEnv->load();

$application = new Application();
$application->add(new ServerCommand());
$application->run();