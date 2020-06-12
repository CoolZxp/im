<?php
namespace app\index\controller;

use think\App;
use think\Controller;

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



    public function index()
    {
        $this -> assign('navSelect','index');
        return $this -> fetch();
    }

    public function aaaa()
    {
        $this -> assign('navSelect','aaaa');
        return $this -> fetch();
    }


}
