<?php

namespace App\RPC\Servers;

use App\RPC\Services\GoodsService;
use App\RPC\Services\UserService;
use Hprose\Symfony\Server;
use Symfony\Component\HttpFoundation\Request;

class UserServer
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function serve(Request $request)
    {
        $server = new Server();

        $userService = new UserService();
        $server->addInstanceMethods($userService, UserService::class, 'user');

        $goodsService = new GoodsService();
        $server->addInstanceMethods($goodsService, GoodsService::class, 'goods');
        $server->debug = true;
        $server->crossDomain = true;

        return $server->start();
    }
}