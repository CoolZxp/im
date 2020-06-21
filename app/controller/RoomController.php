<?php
namespace app\controller;

use app\BaseController;
use app\model\Room;
use app\model\RoomCate;
use think\facade\Request;
use think\facade\View;

class RoomController extends BaseController
{

    public function getRoomList(Room $room) {
        $page = !empty(input('post.page'))?input('post.page'):1;
        $cateId = !empty(input('post.cateId'))?input('post.cateId'):null;

        $roomList = $room -> with(['user','roomCate']);
        if ($cateId != null) {
            $roomList -> where('cate_id',$cateId);
        }
        $roomCount = $roomList -> count();
        $maxPage = intval(ceil($roomCount / 18));
        $roomList = $roomList
            -> page($page,18)
            -> select()
            -> each(function ($item, $key) {
                $item['room_user_num'] = $item -> getAttr('room_user_num');
            });
        return generate_json(SUCCESS,null,[
            'maxPage' => $maxPage,
            'list' => $roomList,
        ]);
    }

    public function getRoomCateList(RoomCate $roomCate) {
        $roomList = $roomCate -> getRoomCateList();
        return generate_json(SUCCESS,null,$roomList);
    }



}
