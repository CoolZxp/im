<?php
namespace app\model;

use Firebase\JWT\JWT;
use think\facade\Cache;
use think\Model;


class User extends Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * createUserToken 生成用户TOKEN
     * @param $userId
     * @return string
     */
    public function createUserToken($userId)
    {
        $info = [
            "iat" => time(), //签发时间
            "nbf" => time(), //生效时间
            "exp" => time() + (7 * 24 * 60 * 60), //token 过期时间1周
            "userId" => $userId
        ];
        $token = JWT::encode($info, config('user.key'),'HS256');
        //生成新token时移除旧Token
        $this -> removeUserToken($userId);
        Cache::set("user:{$userId}:token", $token, time() + (7 * 24 * 60 * 60)); //过期时间1周
        return $token;
    }

    /**
     * 移除用户Token
     * removeUserToken
     * @param $userId
     * @return bool
     */
    public function removeUserToken($userId){
        return Cache::delete("user:{$userId}:token");
    }
    /**
     * getUserTokenInfo 获取TOKEN中用户ID 无效返回false
     * @param $token
     * @return int|bool
     */
    public function getUserTokenInfo($token)
    {
        if (empty($token)) {
            return false;
        }
        JWT::$leeway = 60;
        $info = JWT::decode($token, config('user.key'),['HS256']);
        if (Cache::get("user:{$info -> userId}:token") == $token) {
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
        $userInfo = $this -> find($userId);
        unset($userInfo['user_pasw']);
        return $userInfo;
    }

    public function getUserFaceAttr($value) {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        } else {
            if (request() -> isSsl()) {
                return 'https://' . config('app.app_host') . '/' . $value;
            } else {
                return 'http://' . config('app.app_host') . '/' . $value;
            }
        }
    }


    public function updateUserInfo($postInfo,$num = 0)
    {
        if($postInfo == null){
            return false;
        }
        $userInfo = User::where('user_name',$postInfo['username'])->find();
        if(empty($userInfo))
        {
            return false;
        }else{
            if($num == 1){
                $userInfo['user_autograph'] = $postInfo['qianming'];
            }else{
                $userInfo['nick_name'] = $postInfo['nickname'];
                $userInfo['user_autograph'] = $postInfo['qianming'];
                $userInfo['user_sex'] = $postInfo['usersex'];
                $userInfo['user_birthday'] = strtotime($postInfo['birthday']);
            }
            return $userInfo->save();
        }
    }

    public function updateUserFace($userId,$facePath)
    {
        $userInfo = User::where('id',$userId)->find();
        if(empty($userInfo))
        {
            return false;
        }else{
            $userInfo['user_face'] = $facePath;
            return $userInfo->save();
        }
    }

    /**
     * 设置用户Websocket Fd
     * setUserFd
     * @param $userId
     * @param $fd
     */
    public function setUserFd($userId,$fd) {
        Cache::set("user:fd:fd_{$fd}",$userId);
        Cache::set("user:fd:id_{$userId}",$fd);
    }

    /**
     * 通过Fd获取用户Id
     * getUserIdByFd
     * @param $fd
     * @return mixed
     */
    public function getUserIdByFd($fd) {
        return Cache::get("user:fd:fd_{$fd}");
    }

    /**
     * 通过用户Id获取Fd
     * getFdByUserId
     * @param $userId
     * @return mixed
     */
    public function getFdByUserId($userId) {
        return Cache::get("user:fd:id_{$userId}");
    }


    /**
     * 通过Fd刪除用户Id
     * getUserIdByFd
     * @param $fd
     */
    public function removeUserIdByFd($fd) {
        $userId = $this -> getUserIdByFd($fd);
        Cache::delete("user:fd:fd_{$fd}");
        Cache::delete("user:fd:id_{$userId}");
    }


}
