<?php

namespace app\controller;

use app\BaseController;
use app\model\User;
use think\exception\ValidateException;
use think\facade\Cache;
use think\facade\Filesystem;
use think\facade\Validate;
use think\facade\View;

class UserController extends BaseController
{
    public function login() {
        return View::fetch();
    }


    /**
     * login 登录
     * @return \think\response\Json
     */
    public function userLogin(User $userModel) {
        $postInfo['username'] = input('post.username');
        $postInfo['password'] = input('post.password');
        try {
            $result = $this -> validate($postInfo,'app\validate\UserValidate.login');
        } catch (ValidateException $e) {
            return generate_json(ERROR_VALIDATE,$e -> getError());
        }


        $userInfo = User::where('user_name', $postInfo['username'])->find();
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

    /**
     * regUser 注册
     * @return \think\response\Json
     */
    public function userRegister(User $userModel)
    {
        $postInfo['username'] = input('post.username');
        $postInfo['nickname'] = input('post.nickname');
        $postInfo['password'] = input('post.password');
        try {
            $this -> validate($postInfo,'app\validate\UserValidate.register');
        } catch (ValidateException $e) {
            return generate_json(ERROR_VALIDATE,$e -> getError());
        }

        if (User::where('user_name', $postInfo['username'])->find()) {
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

    /**
     * 退出登录
     * outLogin
     * @return \think\response\Redirect
     */
    public function outLogin() {
        $fromUrl = input('get.from');
        cookie('token',null);
        Cache::delete("user:token:{$this -> request -> userId}:{$this -> request -> token}");
        if (!empty($fromUrl)) {
            return redirect($fromUrl);
        } else {
            return redirect('/');
        }
    }




    /**
     * @return mixed
     */
    public function userInfo(User $userModel)
    {
        View::assign('navSelect','');
        View::assign('userInfo',$userModel -> getUserInfo($this -> request -> userId));
        return View::fetch();
    }

    /**
     * @param User $userModel
     * @param int $num
     * @return \think\response\Json
     */
    public function editUser(User $userModel, $num = 0)
    {
        if(Cache::has("user:{$this -> request -> userId}:edituser_visittime"))
        {
            return generate_json(ERROR_FREQUENTLY);
        }

        $arr = ["expire"=>30];
        cache("user:{$this -> request -> userId}:edituser_visittime",time(),$arr);

        $postInfo['nickname'] = input('post.nickname');
        $postInfo['username'] = input('post.username');
        $postInfo['qianming'] = input('post.qianming');
        $postInfo['birthday'] = input('post.birthday');
        $postInfo['usersex'] = input('post.usersex');
        $result = $num == 0 ? $this -> validate($postInfo,'app\validate\UserValidate.edit') : true;
        if($result !== true) {
            return generate_json(ERROR_VALIDATE);
        }else{
            $a = null;
            if($num == 1)
            {
                $a = $userModel -> updateUserInfo($postInfo,$num);
            }else{
                $a = $userModel -> updateUserInfo($postInfo);
            }
            if($a)
            {
                return generate_json(SUCCESS);
            }else{
                return generate_json(ERROR_AUTH);
            }
        }
    }

    public function upload(){
        // 获取表单上传文件
        $files = request()->file();
        try {
            $savename = [];
            foreach($files as $file) {
            }
        } catch (\think\exception\ValidateException $e) {
            echo $e->getMessage();
        }
    }

    public function uploadImg(User $userModel)
    {
        $file = $this -> request ->file('photoFile');
        try {
            validate(['imgFile'=>[
                    'fileSize' => 1048576,
                    'fileExt' => 'jpg,jpeg,png,bmp,gif',
                    'fileMime' => 'image/jpeg,image/png,image/gif',
            ]])->check(['photoFile' => $file]);
            $savename = Filesystem::disk('public') -> putFile( 'user_face', $file);
            $imgUrl = 'uploads/' . $savename;
        } catch (ValidateException $e) {
            return generate_json(ERROR_USER_UPLOAD,$e -> getError());
        }

        $result = $userModel -> updateUserFace($this -> request -> userId , $imgUrl);
        if (!$result) {
            return generate_json(ERROR_USER_UPLOAD);
        }

        return generate_json(SUCCESS);
    }

    public function getCode($template_name, User $userModel)
    {
        if($template_name == 'info'){
            View::assign('navSelect','');
            View::assign('userInfo',$userModel -> getUserInfo($this -> request -> userId));
        }
        return View::fetch("user/{$template_name}");
    }



}
