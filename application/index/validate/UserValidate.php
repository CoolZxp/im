<?php

namespace app\index\validate;

use think\Validate;

class UserValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'username' => [
            'require',
            'length' => '6,15',
            'alphaNum',
        ],
        'nickname' => [
            'require',
            'length' => '3,15',
            'chsDash'
        ],
        'password' => [
            'require',
            'length' => '6,18',
            'alphaDash'
        ]
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'username.require' => '账号不可为空',
        'username.length' => '账号长度为6-15',
        'username.alphaNum' => '账号仅限字母、数字',
        'nickname.require' => '昵称不可为空',
        'nickname.length' => '昵称长度为3-15',
        'nickname.chsDash' => '昵称仅限汉字、字母、数字和下划线_及破折号-',
        'password.require' => '密码不可为空',
        'password.length' => '密码长度为6-18',
        'password.alphaDash' => '密码仅限字母和数字，下划线_及破折号-',
    ];


    protected $scene = [
        'login'  =>  ['username','password'],
    ];
}
