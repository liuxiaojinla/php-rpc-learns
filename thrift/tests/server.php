<?php

namespace tutorial\php;

error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Services\HelloWorld\HelloWorldProcessor;
use Thrift\Factory\TBinaryProtocolFactory;
use Thrift\Factory\TTransportFactory;
use Thrift\Server\TServerSocket;
use Thrift\Server\TSimpleServer;
use Thrift\TMultiplexedProcessor;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TPhpStream;

$GEN_DIR = realpath(dirname(__FILE__)) . '../gen-php';


/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

/*
 * This is not a stand-alone server.  It should be run as a normal
 * php web script (like through Apache's mod_php) or as a cgi script
 * (like with the included runserver.py).  You can connect to it with
 * THttpClient in any language that supports it.  The PHP tutorial client
 * will work if you pass it the argument "--http".
 */

if (php_sapi_name() == 'cli') {
    ini_set('display_errors', 'stderr');
}

header('Content-Type', 'application/x-thrift');
if (php_sapi_name() == 'cli') {
    echo "\r\n";
}

$handler = new HelloWorldHandler();
$processor = new HelloWorldProcessor($handler);

$tFactory = new TTransportFactory();
$pFactory = new TBinaryProtocolFactory(true, true);
$processor = new TMultiplexedProcessor();
$processor->registerProcessor('hello', $handler);

$transport = new TServerSocket('127.0.0.1', 9090);
$server = new TSimpleServer($processor, $transport, $tFactory, $tFactory, $pFactory, $pFactory);
$server->serve();

$transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
// $protocol = new TBinaryProtocol($transport, true, true);

// $transport->open();
// $processor->process($protocol, $protocol);
// $transport->close();