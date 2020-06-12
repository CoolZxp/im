<?php

namespace app\common\behavior;


use think\exception\ErrorException;

class CodeMsgBehavior
{
    public function run()
    {
        //常量存在 不操作
        try {
            define('SUCCESS',1); //成功
            define('ERROR',-1); //错误
            define('ERROR_VALIDATE',-2); //验证错误
            define('ERROR_USER_NO',-1000); //用户不存在
            define('ERROR_USER_PASW',-1001); //密码错误
            define('ERROR_USER_STATUS',-1002); //状态不正确
            define('ERROR_USER_REG',-1003); //注册失败
            define('ERROR_USER_REG_EXIST',-1004); //用户名已存在
        } catch (ErrorException $e) {

        }

//        $code = config('code.code');
//        foreach ($code as $k => $v) {
//            define($k,$v);
//        }

    }
}