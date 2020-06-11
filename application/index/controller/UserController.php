<?php

namespace app\index\controller;

use think\Controller;
use app\index\model\UserModel;
use think\facade\Cache;
use Firebase\JWT\JWT;

const ERROR_USER_NO = -1000; //用户不存在
const ERROR_USER_PASW = -1001; //密码错误
const ERROR_USER_STATUS = -1002; //状态不正确
const ERROR_USER_REG = -1003; //注册失败
const ERROR_USER_REG_EXIST = -1004; //用户名已存在


class UserController extends Controller
{
    /**
     * login 登录
     * @param $user string 用户名
     * @param $pasw string 密码
     * @return bool|int
     */
    public function login($user, $pasw)
    {
        $userInfo = UserModel::where('user_name', $user)->find();
        if (!$userInfo) {
            return ERROR_USER_NO;
        }
        if ($userInfo['user_pasw'] != $pasw) {
            return ERROR_USER_PASW;
        }

        if ($userInfo['user_status'] != 0) {
            return ERROR_USER_STATUS;
        }
        return SUCCESS_OK;
    }

    /**
     * regUser 注册
     * @param $user string 用户名
     * @param $nickName string 昵称
     * @param $pasw string 密码
     * @return int
     */
    public function regUser($user, $nickName, $pasw)
    {
        if (UserModel::where('user_name', $user)->find()) {
            return ERROR_USER_REG_EXIST;
        }
        $UserModel = new UserModel;
        $UserModel -> user_name = $user;
        $UserModel -> nick_name = $nickName;
        $UserModel -> user_pasw = $pasw;
        if ($UserModel -> save()) {
            return SUCCESS_OK;
        } else {
            return ERROR_USER_REG;
        }
    }

    /**
     * getUserToken 获取用户TOKEN
     * @param $userId
     * @return string
     */
    public function getUserToken($userId)
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
     * checkUserToken 验证用户Token是否有效
     * @param $token
     * @return bool
     */
    public function checkUserToken($token)
    {
        JWT::$leeway = 60;
        $info = JWT::decode($token, config('user.key'),['HS256']);
        return Cache::has("USER_TOKEN:{$info -> userId}:{$token}");
    }
}
