<?php

use Hprose\Http\Client;

require_once __DIR__ . '/../vendor/autoload.php';

$client = new Client([
    'http://127.0.0.1:8024',
], true);
$proxy = $client->useService();

/** @var Hprose\Future $result */
$result = $proxy->hello('小明');
$result->then(function ($value) {
    dump($value);
}, function ($err) {
    dump($err);
});
dump('结束了...');