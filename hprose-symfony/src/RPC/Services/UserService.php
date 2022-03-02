<?php

namespace App\RPC\Services;

class UserService
{
    public function get()
    {
        return [
            'id' => 1,
            'name' => '小明',
        ];
    }

    public function getBalance()
    {
        return [
            'value' => 100,
        ];
    }
}