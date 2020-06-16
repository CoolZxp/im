<?php

namespace app\index\controller;

use app\index\model\RoomModel;
use app\index\model\UserModel;
use think\facade\Session;

class RoomController extends BaseController
{
    public function index(RoomModel $roomModel,UserModel $userModel) {
        $roomId = input('param.id');
        $this -> assign('navSelect','index');
        $roomInfo = $roomModel -> get($roomId);
        if (empty($roomInfo)) {
            $this -> error('聊天室不存在');
        }
        $this -> assign('roomInfo',$roomInfo);
        $userInfo = $userModel -> getUserInfo($this -> request -> userId);
        $this -> assign('userFace',$userInfo['user_face']);
        $this -> assign('nickName',$userInfo['nick_name']);
        $this -> assign('wsUrl',$this -> request -> host());
        return $this -> fetch();
    }
}
