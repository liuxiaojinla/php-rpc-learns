<?php

use Bdy\Micro\Client\ClientManager;
use Services\HelloWorld\HelloWorld_sayHello_args;
use Services\HelloWorld\HelloWorld_sayHello_result;

error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

$clientManager = new ClientManager([
    'hello' => [
        'host' => '127.0.0.1',
        'port' => '9090',
        'args_class' => HelloWorld_sayHello_args::class,
        'result_class' => HelloWorld_sayHello_result::class,
    ],
]);
while (1) {
    var_dump($clientManager->hello->sayHello('小明', '59'));
    usleep(10000);
}

// $config = [
//     'name' => 'hello',
//     'host' => '127.0.0.1',
//     'port' => '9090',
//     'args_class' => HelloWorld_sayHello_args::class,
//     'result_class' => HelloWorld_sayHello_result::class,
// ];
//
// if (isset($config['http']) && $config['http']) {
//     $socket = new THttpClient($config['host'], $config['port'], $config['uri'] ?? '');
// } else {
//     $socket = new TSocket($config['host'], $config['port']);
// }
//
// $transport = new TBufferedTransport($socket, 1024 * 1024, 1024 * 100);
// $protocol = new TBinaryProtocol($transport);
// $multiplexedProtocol = new TMultiplexedProtocol($protocol, $config['name']);
//
// $client = new HelloWorldClient($multiplexedProtocol);
//
// $transport->open();
//
// var_dump($client->sayHello('小明', 59));
//
// $transport->close();