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
use think\facade\Route;

Route::group(function () {
    Route::get('outLogin','index/User/outLogin');

    Route::group(function () {
        Route::get('user', 'index/User/userInfo');
        Route::rule('room/:id', 'index/Room/index');
    }) -> middleware(\app\middleware\Header::class);

}) -> middleware(\app\middleware\ViewAuth::class);

Route::group(function () {
    Route::post('editUser','index/User/editUser');
    Route::post('uploadImg','index/User/uploadImg');
    Route::post('user/:tel', 'index/User/getCode');

}) -> middleware(\app\middleware\Auth::class);


Route::get('/', 'index/Index/index')
    -> middleware(\app\middleware\Header::class);


Route::post('login', 'index/User/login');
Route::post('register', 'index/User/register');



