<?php

use Hprose\Http\Client;

require_once __DIR__ . '/../vendor/autoload.php';

$client = new Client([
    'http://127.0.0.1:8024',
], false);
$proxy = $client->useService();

/** @var Hprose\Future $result */
$result = $proxy->hello('小明');
dump($result);