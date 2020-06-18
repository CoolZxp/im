<?php
namespace app\controller;

use app\BaseController;
use app\model\RoomCate;
use app\model\Room;
use think\facade\Request;
use think\facade\View;

class IndexController extends BaseController
{


    public function index(Room $roomModel, RoomCate $roomCateModel)
    {
        $page = Request::get('page');
        $cateId = Request::get('cateId');
        $cateId = $cateId == '0'?null:$cateId;
        $roomList = $roomModel -> getRoomList($cateId);
        $roomListNum = $roomList -> count();
        $roomCateList = $roomCateModel -> getRoomCateList();
        View::assign('cateId',$cateId);
        View::assign('roomList',$roomList);
        View::assign('roomListNum',$roomListNum);
        View::assign('roomListAddNum',4 - ($roomListNum % 4));
        View::assign('roomCateList',$roomCateList);
        View::assign('navSelect','index');
        return View::fetch();
    }
}
