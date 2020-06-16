<?php
namespace app\index\widget;

use think\Controller;
use app\index\model\UserModel;

class CommonWidget extends Controller
{
    /**
     * header 渲染header
     */
    public function header() {
        $token = cookie('token');
        if (!empty($token)) {
            $userModel = new UserModel;
            $userId = $userModel -> getUserTokenInfo($token);
            if ($userId !== false) {
                $userInfo = $userModel -> getUserInfo($userId);
                $this -> assign('isLogin',true);
                $this -> assign('userFace',$userInfo['user_face']);
                $this -> assign('nickName',$userInfo['nick_name']);
            } else {
                $this -> assign('isLogin',false);
            }
        } else {
            $this -> assign('isLogin',false);
        }
        return $this -> fetch('common/header');
    }
}