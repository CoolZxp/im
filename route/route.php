<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('/', 'index/Index/index');

Route::get('user', 'index/User/userInfo')
    -> middleware('ViewAuth');
Route::post('login', 'index/User/login');
Route::get('outLogin','index/User/outLogin')
    -> middleware('ViewAuth');
Route::post('register', 'index/User/register');
Route::post('editUser','index/User/editUser')
    -> middleware('Auth');
Route::post('uploadImg','index/User/uploadImg')
    -> middleware('Auth');
Route::rule('room/:id', 'index/Room/index')
    -> middleware('ViewAuth');

Route::post('user/:tel', 'index/User/getCode')
    -> middleware('Auth');

return [

];