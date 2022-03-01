<?php

use Hprose\Socket\Client;

require_once __DIR__ . '/../vendor/autoload.php';

$client = new Client([
    'tcp://127.0.0.1:8025',
], false);
$proxy = $client->useService();
$result = $proxy->hello('小明');
dump($result);