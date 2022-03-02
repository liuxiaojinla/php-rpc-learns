<?php

namespace App\RPC\Clients;


use App\RPC\Services\UserService;
use Hprose\Http\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \Hprose\Proxy|UserService
     */
    protected $userService;

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->client = new Client([
            'http://127.0.0.1:8100/rpc',
        ], false);
        $this->client->timeout = 1000;

        /** @var UserService $userService */
        $this->userService = $this->client->useService([], 'user');
    }

    /**
     * 调用服务
     * @return JsonResponse
     */
    public function call()
    {
        $result = $this->userService->get();
        $balance = $this->userService->getBalance();
        $result['balance'] = $balance;

        return new JsonResponse($result);
    }
}