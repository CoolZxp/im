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
    Route::get('outLogin','User/outLogin');

    Route::group(function () {
        Route::get('user', 'User/userInfo');
        Route::rule('room/:id', 'Room/index');
    }) -> middleware(\app\middleware\Header::class);

}) -> middleware(\app\middleware\ViewAuth::class);

Route::group(function () {
    Route::post('editUser','User/editUser');
    Route::post('uploadImg','User/uploadImg');
    Route::post('user/:tel', 'User/getCode');

}) -> middleware(\app\middleware\Auth::class);


Route::get('/', 'Index/index')
    -> middleware(\app\middleware\Header::class);


Route::post('login', 'User/login');
Route::post('register', 'User/register');



