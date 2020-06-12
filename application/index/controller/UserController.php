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
    public function login() {
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
                    'token' => $this->getUserToken($userInfo['id']),
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

    /**
     * getUserToken 获取用户TOKEN
     * @param $userId
     * @return string
     */
    protected function getUserToken($userId)
    {
        $info = [
            "iat" => time(), //签发时间
            "nbf" => time(), //生效时间
            "exp" => time() + (7 * 24 * 60 * 60), //token 过期时间1周
            "userId" => $userId
        ];
        $token = JWT::encode($info, config('user.key'),'HS256');
        Cache::set("USER_TOKEN:{$userId}:{$token}", time(), time() + (7 * 24 * 60 * 60)); //过期时间1周
        return $token;
    }

    /**
     * getUserTokenInfo 获取TOKEN中用户ID 无效返回false
     * @param $token
     * @return int|bool
     */
    public function getUserTokenInfo($token)
    {
        JWT::$leeway = 60;
        $info = JWT::decode($token, config('user.key'),['HS256']);
        $is_has = Cache::has("USER_TOKEN:{$info -> userId}:{$token}");
        if ($is_has) {
            return $info -> userId;
        } else {
            return false;
        }
    }

    /**
     * getUserInfo 获取用户信息
     * @param $userId
     * @return mixed
     */
    public function getUserInfo($userId) {
        $userInfo = UserModel::get($userId);
        unset($userInfo['user_pasw']);
        return $userInfo;
    }

}
