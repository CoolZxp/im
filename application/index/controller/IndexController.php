<?php
namespace app\index\controller;

use think\App;
use think\Controller;

class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $token = cookie('token');
        if (!empty($token)) {
            $UserController = new UserController;
//            $UserController ->
//            $this -> assign('isLogin',true);
        }
    }

    public function index()
    {
        return view() -> assign([
            'navSelect' => 'index',
        ]);
    }

    public function aaaa()
    {
        return view() -> assign([
            'navSelect' => 'aaaa',
        ]);
    }


    public function login()
    {
        return view('user/login');
    }

}
