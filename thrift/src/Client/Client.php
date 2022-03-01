<?php

namespace Bdy\Micro\Client;

use Thrift\Exception\TApplicationException;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TBinaryProtocolAccelerated;
use Thrift\Protocol\TMultiplexedProtocol;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TSocket;
use Thrift\Transport\TTransport;
use Thrift\Type\TMessageType;

class Client
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var THttpClient|TSocket
     */
    protected $socket;

    /**
     * @var TTransport
     */
    protected $input_ = null;

    /**
     * @var TTransport
     */
    protected $output_ = null;

    /**
     * @var int
     */
    protected $seqid_ = 0;

    /**
     * @var TTransport
     */
    protected $transport;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        if (isset($config['http']) && $config['http']) {
            $socket = new THttpClient($config['host'], $config['port'], $config['uri'] ?? '');
        } else {
            $socket = new TSocket($config['host'], $config['port']);
        }

        $this->socket = $socket;

        $transport = new TBufferedTransport($socket, 1024 * 1024, 1024 * 100);
        $protocol = new TBinaryProtocol($transport);
        $multiplexedProtocol = new TMultiplexedProtocol($protocol, $config['name']);

        $this->transport = $transport;
        $this->input_ = $multiplexedProtocol;
        $this->output_ = $multiplexedProtocol;
    }

    protected function getArgsClass()
    {
        return $this->config['args_class'] ?? '';
    }

    protected function getResultClass()
    {
        return $this->config['result_class'] ?? '';
    }

    protected function newArgsInstance($arguments)
    {
        $class = $this->getArgsClass();
        $instance = new $class;

        $classVarList = $class::$_TSPEC;

        foreach ($arguments as $key => $argument) {
            $varName = $classVarList[$key + 1]['var'];
            $instance->{$varName} = $argument;
        }

        return $instance;
    }


    protected function send(string $name, $arguments)
    {
        $argsInstance = $this->newArgsInstance($arguments);
        $bin_accel = ($this->output_ instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_write_binary');
        if ($bin_accel) {
            thrift_protocol_write_binary(
                $this->output_,
                $name,
                TMessageType::CALL,
                $argsInstance,
                $this->seqid_,
                $this->output_->isStrictWrite()
            );
        } else {
            $this->output_->writeMessageBegin($name, TMessageType::CALL, $this->seqid_);
            $argsInstance->write($this->output_);
            $this->output_->writeMessageEnd();
            $this->output_->getTransport()->flush();
        }
    }

    protected function receive(string $name)
    {
        $bin_accel = ($this->input_ instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_read_binary');
        if ($bin_accel) {
            $result = thrift_protocol_read_binary(
                $this->input_,
                $this->getResultClass(),
                $this->input_->isStrictRead()
            );
        } else {
            $rseqid = 0;
            $fname = null;
            $mtype = 0;

            $this->input_->readMessageBegin($fname, $mtype, $rseqid);
            if ($mtype == TMessageType::EXCEPTION) {
                $x = new TApplicationException();
                $x->read($this->input_);
                $this->input_->readMessageEnd();
                throw $x;
            }
            $result = new \Services\HelloWorld\HelloWorld_sayHello_result();
            $result->read($this->input_);
            $this->input_->readMessageEnd();
        }
        if ($result->success !== null) {
            return $result->success;
        }
        throw new \Exception($name . ' failed: unknown result');
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed|string|null
     * @throws TApplicationException
     */
    protected function execute($name, $arguments)
    {
        $this->send($name, $arguments);

        return $this->receive($name);
    }

    /**
     * @inheritDoc
     */
    public function __call($name, $arguments)
    {
        $this->transport->open();

        $result = $this->execute($name, $arguments);

        $this->transport->close();

        return $result;
    }
}