<?php

namespace app\index\controller;

use think\App;
use think\Controller;

class RoomController extends BaseController
{
    protected $viewAuth = [
        'Room\index'
    ];
    protected $methodAuth = [];

    public function index() {
        $this -> assign('navSelect','index');
        return $this -> fetch();
    }
}
