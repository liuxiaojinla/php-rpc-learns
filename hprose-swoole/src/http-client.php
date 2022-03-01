<?php

use Hprose\Swoole\Http\Client;

require_once __DIR__ . '/../vendor/autoload.php';

$client = new Client([
    'http://127.0.0.1:8087',
], false);
$userService = $client->useService();

/** @var Hprose\Future $result */
$result = $userService->hello("小虎");
$result->then(function ($value) {
    dump($value);
}, function ($err) {
    dump($err);
    throw new Exception('失败了');
});
dump('结束了...');