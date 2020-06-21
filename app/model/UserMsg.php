<?php
namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;


class UserMsg extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    //关联自身User
    public function user()
    {
        return $this -> belongsTo(User::class,'user_id') -> bind([
            'user_name',
            'nick_name',
            'user_face',
        ]);
    }

    //关联发送User
    public function toUser()
    {
        return $this -> belongsTo(User::class,'to_user_id') -> bind([
            'to_user_name' => 'user_name',
            'to_nick_name' => 'nick_name',
            'to_user_face' => 'user_face',
        ]);
    }
    //关联发送Room
    public function toRoom()
    {
        return $this -> belongsTo(Room::class,'to_room_id') -> bind([
            'to_room_name' => 'room_name',
            'to_room_img' => 'room_img',
        ]);
    }

    /**
     * 获取用户消息列表
     * getUserMsgList
     * @param $userId
     * @return \think\Collection
     */
    public function getUserMsgList($userId)
    {
        return $this -> with(['user','toUser','toRoom']) -> where([
            'user_id' => $userId,
        ]) -> order('update_time','desc') -> select();
    }



//    public function getUserMsgInfo($id)
//    {
//        $roomRecord = new RoomRecord();
//        $userMsg = $this -> find($id);
//        return $roomRecord -> getRoomRecordList($userMsg['to_room_id']);
//    }

}
