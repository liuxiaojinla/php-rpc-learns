<?php

namespace Bdy\Micro\Client;

class ClientManager
{
    /**
     * @var ServerNodeLoader
     */
    protected $serverNodeLoader;

    /**
     * @var array
     */
    protected $clients;

    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @param ServerNodeLoader|null $serverNodeLoader
     */
    public function __construct(array $clients = [], ServerNodeLoader $serverNodeLoader = null)
    {
        $this->clients = $clients;
        $this->serverNodeLoader = $serverNodeLoader ?: new ServerNodeLoader();
    }

    /**
     * @param $name
     * @return Client
     */
    public function get($name): Client
    {
        if (!isset($this->instances[$name])) {
            $this->instances[$name] = $this->create($name);
        }

        return $this->instances[$name];
    }

    protected function create($name): Client
    {
        if (!isset($this->clients[$name])) {
            throw new \RuntimeException("client \"$name\" not defined.");
        }

        $config = $this->clients[$name];
        $config['name'] = $name;

        return new Client($config);
    }

    public function __get($name)
    {
        return $this->get($name);
    }
}