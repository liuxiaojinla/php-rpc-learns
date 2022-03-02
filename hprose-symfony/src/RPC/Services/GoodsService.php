<?php

namespace App\RPC\Services;

class GoodsService
{
    public function list()
    {
        return [
            [
                'id' => 1,
                'title' => '商品名称',
            ],
        ];
    }

    public function get($id)
    {
        return [
            'id' => $id,
            'title' => '商品名称：'.$id,
        ];
    }
}