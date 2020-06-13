<?php
namespace app\index\controller;

use app\index\model\RoomCateModel;
use app\index\model\RoomModel;
use think\App;
use think\Controller;
use think\facade\Request;

class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $token = cookie('token');
        if (!empty($token)) {
            $UserController = new UserController;
            $userId = $UserController -> getUserTokenInfo($token);
            if ($userId !== false) {
                $userInfo = $UserController -> getUserInfo($userId);
                $this -> assign('isLogin',true);
                $this -> assign('userFace',$userInfo['user_face']);
                $this -> assign('UserName',$userInfo['user_name']);
            } else {
                $this -> assign('isLogin',false);
            }
        } else {
            $this -> assign('isLogin',false);
        }

    }



    public function index(RoomModel $roomModel,RoomCateModel $roomCateModel)
    {
        $cateId = Request::get('cateId');
        $roomList = $roomModel -> getRoomList($cateId);
        $roomListNum = $roomList -> count();
        $roomCateList = $roomCateModel -> getRoomCateList();

        $this -> assign('roomList',$roomList);
        $this -> assign('roomListNum',$roomListNum);
        $this -> assign('roomListAddNum',4 - ($roomListNum % 4));
        $this -> assign('roomCateList',$roomCateList);
        $this -> assign('navSelect','index');

        return $this -> fetch();
    }


}
