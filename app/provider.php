<?php
use app\ExceptionHandle;
use app\Request;

// 容器Provider定义文件
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class,
    'User' => \app\model\User::class,
    'Room' => \app\model\Room::class,
    'RoomCate' => \app\model\RoomCate::class,
    'Redis' => \redis\Redis::class,
//    'SwooleSocket' => \app\http\SwooleSocket::class,
];
