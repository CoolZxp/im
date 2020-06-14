<?php

namespace app\index\controller;

use think\App;
use think\Controller;

class RoomController extends BaseController
{
    public function index() {
        $this -> assign('navSelect','index');
        return $this -> fetch();
    }
}
