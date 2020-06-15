<?php
namespace app\index\controller;

use app\index\model\UserModel;
use think\Controller;
use think\facade\Request;


class BaseController extends Controller
{
    //需要权限验证
    protected $viewAuth = [];
    protected $methodAuth = [];
    protected $userId = null;

    public function __construct()
    {
        parent::__construct();
        $actionUrl = strtolower(Request::controller() . '\\' . Request::action());
        $userModel = new UserModel;
        foreach ($this -> viewAuth as $v) {
            if ($actionUrl == strtolower($v)) {
                $token = cookie('token');
                if (!empty($token)) {
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
            }
        }

        foreach ($this -> methodAuth as $v) {
            if ($actionUrl == $v) {
                $token = cookie('token');
                if (!empty($token)) {
                    $userId = $userModel -> getUserTokenInfo($token);
                    if ($userId !== false) {
                        $this -> userId = $userId;
                    }
                }
            }
        }

    }



}
