<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用容器绑定定义

return [
    'UserModel' => \app\index\model\UserModel::class,
    'RoomModel' => \app\index\model\RoomModel::class,
    'RoomCateModel' => \app\index\model\RoomCateModel::class,
    'Redis' => \redis\Redis::class,
    'SwooleSocket' => \app\http\SwooleSocket::class,
];
