<?php
namespace app\index\controller;

use think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $UserController = new UserController();
//        DUMP($UserController -> login('1206799565','123456789'));
//        DUMP($UserController -> getUserToken(1));
        DUMP($UserController -> checkUserToken("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1OTE4ODc1MjIsIm5iZiI6MTU5MTg4NzUyMiwiZXhwIjoxNTkyNDkyMzIyLCJ1c2VySWQiOjF9.nWLcZphwKkGORo3YV5wrI_CvobMsvW_1yP8QSGOn68s"));


        return view() -> assign([
            'nav_select' => 'index',
        ]);
    }
    public function aaaa()
    {
        return view() -> assign([
            'nav_select' => 'aaaa',
        ]);
    }

}
