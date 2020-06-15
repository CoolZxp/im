<?php
namespace app\index\controller;

use app\index\model\RoomCateModel;
use app\index\model\RoomModel;
use think\facade\Request;

class IndexController extends BaseController
{
    protected $viewAuth = [
        'Index\index'
    ];
    protected $methodAuth = [
        'Index\index'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function index(RoomModel $roomModel,RoomCateModel $roomCateModel)
    {
        $this -> getHeaderUserInfo();
        $page = Request::get('page');
        $cateId = Request::get('cateId');
        $cateId = $cateId == '0'?null:$cateId;
        $roomList = $roomModel -> getRoomList($cateId);
        $roomListNum = $roomList -> count();
        $roomCateList = $roomCateModel -> getRoomCateList();
        $this -> assign('cateId',$cateId);
        $this -> assign('roomList',$roomList);
        $this -> assign('roomListNum',$roomListNum);
        $this -> assign('roomListAddNum',4 - ($roomListNum % 4));
        $this -> assign('roomCateList',$roomCateList);
        $this -> assign('navSelect','index');
        return $this -> fetch();
    }


}
