<?php
$http = new Swoole\Http\Server("0.0.0.0", 10241);
$http->on('request', function ($request, $response) {
	$response->end("<h1>Hello Swoole With Shop Service. #" . rand(1000, 9999) . "</h1>");
});
$http->start();
