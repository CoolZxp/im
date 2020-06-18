<?php
namespace app\controller;

use app\BaseController;
use app\model\Room;
use app\model\User;
use think\facade\Session;
use think\facade\View;

class RoomController extends BaseController
{
    public function index(Room $roomModel, User $userModel) {
        $roomId = input('param.id');
        View::assign('navSelect','index');
        $roomInfo = $roomModel -> find($roomId);
        if (empty($roomInfo)) {
            $this -> error('聊天室不存在');
        }
        View::assign('roomInfo',$roomInfo);
        $userInfo = $userModel -> getUserInfo($this -> request -> userId);
        View::assign('userFace',$userInfo['user_face']);
        View::assign('nickName',$userInfo['nick_name']);
        View::assign('wsUrl',$this -> request -> host());
        return View::fetch();
    }
}
