<?php

namespace Bdy\Micro\Tests\Handlers;

class HelloWorldHandler implements \Services\HelloWorld\HelloWorldIf
{

    public function sayHello($name, $key)
    {
        // TODO: Implement sayHello() method.
        var_dump('client:sayHello:' . $name . ',' . $key);

        return $name;
    }

    public function sayWorld($name)
    {
        var_dump('client:sayWorld:' . $name);

        return $name;
    }
}