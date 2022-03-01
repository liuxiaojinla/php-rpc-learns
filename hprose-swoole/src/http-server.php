<?php

use Hprose\Swoole\Server;

require_once __DIR__ . '/../vendor/autoload.php';

function hello($name)
{
    return "Hello $name!";
}

$server = new Server('http://0.0.0.0:8087');
$server->addFunction('hello');

dump('server listen...');
$server->start();