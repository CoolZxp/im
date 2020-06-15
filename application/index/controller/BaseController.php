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
        $token = cookie('token');

        foreach ($this -> viewAuth as $v) {
            if ($actionUrl == strtolower($v)) {
                if (!empty($token)) {
                    $userId = $userModel -> getUserTokenInfo($token);
                    if ($userId !== false) {
                        $this -> userId = $userId;
                    } else {
                        $this -> error('未知错误');
                    }
                } else {
                    $this -> error('未知错误');
                }
                break;
            }
        }

        foreach ($this -> methodAuth as $v) {
            if ($actionUrl == strtolower($v)) {
                $json = [];
                if (!empty($token)) {
                    $userId = $userModel -> getUserTokenInfo($token);
                    if ($userId !== false) {
                        $this -> userId = $userId;
                    } else {
                        $json['code'] = ERROR_AUTH;
                        $json['msg'] = get_code_msg(ERROR_AUTH);
                    }
                } else {
                    $json['code'] = ERROR_AUTH;
                    $json['msg'] = get_code_msg(ERROR_AUTH);
                }
                return json($json);
            }
        }

    }


    /**
     * getViewUserInfo 获取Header用户登录信息
     */
    protected function getHeaderUserInfo() {
        $token = cookie('token');
        if (!empty($token)) {
            $userModel = new UserModel;
            $userId = $userModel -> getUserTokenInfo($token);
            if ($userId !== false) {
                $userInfo = $userModel -> getUserInfo($userId);
                $this -> assign('isLogin',true);
                $this -> assign('userFace',$userInfo['user_face']);
                $this -> assign('userName',$userInfo['user_name']);
            } else {
                $this -> assign('isLogin',false);
            }
        } else {
            $this -> assign('isLogin',false);
        }
    }




}
