<?php

namespace app\index\controller;

class RoomController extends BaseController
{
    public function index() {
        $this -> assign('navSelect','index');
        return $this -> fetch();
    }
}
