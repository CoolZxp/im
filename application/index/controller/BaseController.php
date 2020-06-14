<?php
namespace app\index\controller;

use app\index\model\UserModel;
use think\Controller;




class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $token = cookie('token');
        if (!empty($token)) {
            $userModel = new UserModel;
            $userId = $userModel -> getUserTokenInfo($token);
            if ($userId !== false) {
                $userInfo = $userModel -> getUserInfo($userId);
                $this -> assign('isLogin',true);
                $this -> assign('userFace',$userInfo['user_face']);
                $this -> assign('UserName',$userInfo['user_name']);
            } else {
                $this -> assign('isLogin',false);
            }
        } else {
            $this -> assign('isLogin',false);
        }
        $this -> assign('appName',config('app_name'));
    }



}
