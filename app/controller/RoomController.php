<?php
namespace app\controller;

use app\BaseController;
use app\model\Room;

class RoomController extends BaseController
{

    public function getSelfRoomList(Room $room) {
        $page = !empty(input('post.page'))?input('post.page'):1;
        $cateId = !empty(input('post.cateId'))?input('post.cateId'):null;
        $roomList = Room::hasWhere('roomUser',['user_id' => $this -> request -> userId])
            -> with(['user','roomCate']);
        if ($cateId != null) {
            $roomList -> where('cate_id',$cateId);
        }
        $roomCount = $roomList -> count();
        $maxPage = intval(ceil($roomCount / 18));
        $roomList = $roomList -> withCount('roomUser',false)
            -> page($page,18)
            -> select();
        return generate_json(SUCCESS,null,[
            'maxPage' => $maxPage,
            'list' => $roomList,
        ]);
    }
}
