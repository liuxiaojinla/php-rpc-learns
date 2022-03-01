<?php

use Hprose\Socket\Server;

require_once __DIR__ . '/../vendor/autoload.php';

function hello(string $name): string
{
    return 'hello ' . $name . '!';
}

$server = new Server('tcp://0.0.0.0:8025');
$server->addFunction('hello', 'hello');
$server->debug = true;
$server->crossDomain = true;
$server->onAccept = function (\stdClass $context) {
    dump("新的客户端：" . stream_socket_get_name($context->socket, true));
};
dump('server listen...');
$server->start();