<?php

use Hprose\Socket\Client;

require_once __DIR__ . '/../vendor/autoload.php';

while (true) {
    $client = new Client([
        'tcp://127.0.0.1:8025',
    ], true);
    $proxy = $client->useService();

    /** @var Hprose\Future $result */
    $result = $proxy->hello('小明');


    $result->then(function ($value) {
        dump($value);
    }, function ($err) {
        dump($err);
        throw new Exception("失败了");
    });
    dump('结束了...');
    // usleep(10000);
}
