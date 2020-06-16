<?php

namespace app\index\controller;
use app\index\model\UserModel;
use think\facade\Cache;

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
            return generate_json(ERROR_VALIDATE);
        } else {
            $userInfo = UserModel::where('user_name', $postInfo['username'])->find();
            if (!$userInfo) {
                return generate_json(ERROR_USER_NO);
            }else if (!password_verify($postInfo['password'],$userInfo['user_pasw'])) {
                //ALNB
                return generate_json(ERROR_USER_PASW);
            }else if ($userInfo['user_status'] != 0) {
                return generate_json(ERROR_USER_STATUS);
            } else {
                return generate_json(SUCCESS,null,[
                    'token' => $userModel -> getUserToken($userInfo['id']),
                ]);
            }
        }
    }

    /**
     * regUser 注册
     * @return \think\response\Json
     */
    public function register(UserModel $userModel)
    {
        $postInfo['username'] = input('post.username');
        $postInfo['nickname'] = input('post.nickname');
        $postInfo['password'] = input('post.password');
        $result = $this -> validate($postInfo,'app\index\validate\UserValidate.register');
        if ($result !== true) {
            return generate_json(ERROR_VALIDATE);
        } else {
            if (UserModel::where('user_name', $postInfo['username'])->find()) {
                return generate_json(ERROR_USER_REG_EXIST);
            } else {
                $userModel->user_name = $postInfo['username'];
                $userModel->nick_name = $postInfo['nickname'];
                $userModel->user_pasw = password_hash($postInfo['password'],PASSWORD_DEFAULT);
                if ($userModel->save()) {
                    return generate_json(SUCCESS);
                } else {
                    return generate_json(ERROR_USER_REG);
                }
            }
        }
    }

    public function outLogin() {
        $fromUrl = input('get.from');
        cookie('token',null);
        Cache::rm("user:token:{$this -> request -> userId}:{$this -> request -> token}");
        if (!empty($fromUrl)) {
            $this -> redirect($fromUrl);
        } else {
            $this -> redirect('/');
        }
    }

    /**
     * @return mixed
     */
    public function userInfo(UserModel $userModel)
    {
        $this -> assign('navSelect','');
        $this -> assign('userInfo',$userModel -> getUserInfo($this -> request -> userId));
        return $this -> fetch();
    }

    /**
     * @return \think\response\Json
     */
    public function editUser(UserModel $userModel)
    {
        $postInfo['nickname'] = input('post.nickname');
        $postInfo['username'] = input('post.username');
        $postInfo['qianming'] = input('post.qianming');
        $postInfo['birthday'] = input('post.birthday');
        $postInfo['usersex'] = input('post.usersex');
        $result = $this -> validate($postInfo,'app\index\validate\UserValidate.edit');
        if($result !== true) {
            return generate_json(ERROR_VALIDATE);
        }else{
            $a = $userModel -> updateUserInfo($postInfo);
            if($a)
            {
                return generate_json(SUCCESS);
            }else{
                return generate_json(ERROR_USER_REG);
            }
        }
    }
}
