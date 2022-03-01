<?php

namespace Bdy\Micro;

use Thrift\Factory\TBinaryProtocolFactory;
use Thrift\Factory\TTransportFactory;
use Thrift\Server\TForkingServer;
use Thrift\Server\TServerSocket;
use Thrift\Server\TSimpleServer;
use Thrift\TMultiplexedProcessor;

class Application
{
    /**
     * @var \string[][]
     */
    protected $config = [
        'server' => [
            'host' => '127.0.0.1',
            'port' => '9090',
        ],
    ];

    /**
     * @param array $processors
     * @return void
     * @throws \Thrift\Exception\TException
     */
    public function run(array $processors)
    {
        $serverSocket = new TServerSocket($this->config['server']['host'], $this->config['server']['port']);

        $processorManager = $this->registerProcessors($processors);

        // $transport = new TFramedTransport($serverSocket);
        // $protocol = new TBinaryProtocol($transport, true, true);

        $tFactory = new TTransportFactory();
        $pFactory = new TBinaryProtocolFactory(true, true);
        $server = new TForkingServer($processorManager, $serverSocket, $tFactory, $tFactory, $pFactory, $pFactory);
        $server = new TSimpleServer($processorManager, $serverSocket, $tFactory, $tFactory, $pFactory, $pFactory);
        $server->serve();
    }

    /**
     * @param array $processors
     * @return TMultiplexedProcessor
     */
    protected function registerProcessors(array $processors): TMultiplexedProcessor
    {
        $processorManager = new TMultiplexedProcessor();

        foreach ($processors as $key => $processor) {
            $processorManager->registerProcessor($key, $processor);
        }

        return $processorManager;
    }
}