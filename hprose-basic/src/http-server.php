<?php

use Hprose\Http\Server;

require_once __DIR__ . '/../vendor/autoload.php';

function hello(string $name): string
{
    return "hello " . $name . "!";
}

$server = new Server();
$server->addFunction('hello', 'hello');
$server->debug = true;
$server->crossDomain = true;
$server->start();