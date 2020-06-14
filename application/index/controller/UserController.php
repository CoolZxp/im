<?php

namespace app\index\controller;

use think\Controller;
use app\index\model\UserModel;
use think\facade\Cache;
use Firebase\JWT\JWT;




class UserController extends BaseController
{

    /**
     * login 登录
     * @return \think\response\Json
     */
    public function login(UserModel $userModel) {
        $postInfo['username'] = input('post.username');
        $postInfo['password'] = input('post.password');
        $result = $this -> validate($postInfo,'app\index\validate\UserValidate.login');

        if ($result !== true) {
            $json['code'] = ERROR_VALIDATE;
            $json['msg'] = $result;
        } else {
            $userInfo = UserModel::where('user_name', $postInfo['username'])->find();
            if (!$userInfo) {
                $json['code'] = ERROR_USER_NO;
                $json['msg'] = get_code_msg(ERROR_USER_NO);
            }else if ($userInfo['user_pasw'] != md5($postInfo['password'])) {
                $json['code'] = ERROR_USER_PASW;
                $json['msg'] = get_code_msg(ERROR_USER_PASW);
            }else if ($userInfo['user_status'] != 0) {
                $json['code'] = ERROR_USER_STATUS;
                $json['msg'] = get_code_msg(ERROR_USER_STATUS);
            } else {
                $json['code'] = SUCCESS;
                $json['msg'] = get_code_msg(SUCCESS);
                $json['data'] = [
                    'token' => $userModel -> getUserToken($userInfo['id']),
                ];
            }
        }
        return json($json);
    }

    /**
     * regUser 注册
     * @return \think\response\Json
     */
    public function register()
    {
        $postInfo['username'] = input('post.username');
        $postInfo['nickname'] = input('post.nickname');
        $postInfo['password'] = input('post.password');
        $result = $this -> validate($postInfo,'app\index\validate\UserValidate');
        if ($result !== true) {
            $json['code'] = ERROR_VALIDATE;
            $json['msg'] = $result;
        } else {
            if (UserModel::where('user_name', $postInfo['username'])->find()) {
                $json['code'] = ERROR_USER_REG_EXIST;
                $json['msg'] = get_code_msg(ERROR_USER_REG_EXIST);
            } else {
                $UserModel = new UserModel;
                $UserModel->user_name = $postInfo['username'];
                $UserModel->nick_name = $postInfo['nickname'];
                $UserModel->user_pasw = md5($postInfo['password']);
                if ($UserModel->save()) {
                    $json['code'] = SUCCESS;
                    $json['msg'] = get_code_msg(SUCCESS);
                } else {
                    $json['code'] = ERROR_USER_REG;
                    $json['msg'] = get_code_msg(ERROR_USER_REG);
                }
            }
        }

        return json($json);
    }


}
