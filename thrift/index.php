<?php

use Bdy\Micro\Application;
use Bdy\Micro\Tests\Handlers\HelloWorldHandler;
use Services\HelloWorld\HelloWorldProcessor;

error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

if (php_sapi_name() == 'cli') {
    ini_set('display_errors', 'stderr');
    echo "\r\n";
}


echo '服务已启动！！！';
$app = new Application();
$app->run([
    'hello' => new HelloWorldProcessor(new HelloWorldHandler()),
]);