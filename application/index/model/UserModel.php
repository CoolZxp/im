<?php
namespace app\index\model;

use Firebase\JWT\JWT;
use think\facade\Cache;
use think\Model;


class UserModel extends Model
{

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
        Cache::set("user:token:{$userId}:{$token}", time(), time() + (7 * 24 * 60 * 60)); //过期时间1周
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
        $is_has = Cache::has("user:token:{$info -> userId}:{$token}");
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
