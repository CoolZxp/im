<?php
namespace app\model;

use think\Model;


class Room extends Model
{
    //关联User
    public function user()
    {
        return $this -> belongsTo(User::class) -> bind([
            'user_name',
            'nick_name',
            'user_face',
        ]);
    }
    //关联RoomCate
    public function roomCate()
    {
        return $this -> belongsTo(RoomCate::class,'cate_id') -> bind([
            'cate_name',
        ]);
    }


    /**
     * 获取在线人数
     * getRoomUserNumAttr
     * @param $value
     * @param $data
     * @return bool|int
     */
    public function getRoomUserNumAttr($value,$data) {
        $redis = app('Redis');
        $userNum = $redis -> hLen("room:{$data['id']}");
        return $userNum;
    }

    /**
     * 获取在线用户列表（获取器）
     * getRoomUserListAttr
     * @param $value
     * @param $data
     * @return \think\Collection
     */
    public function getRoomUserListAttr($value,$data) {
        $userList = $this -> getRoomUserList($data['id']);
        $userIdList = array_values($userList);
        return User::field(['id','nick_name','user_face']) -> where(['id' => $userIdList]) -> select();
    }

    /**
     * 获取房间在线用户列表
     * getRoomUserList
     * @param $roomId
     * @return mixed
     */
    public function getRoomUserList($roomId) {
        $redis = app('Redis');
        $userList = $redis -> hGetAll("room:{$roomId}");
        return $userList;
    }

    /**
     * 添加房间用户
     * addRoomUser
     * @param $roomId
     * @param $userId
     * @param $fd
     */
    public function addRoomUser($roomId,$userId,$fd) {
        $redis = app('Redis');
        $redis -> hSet("room:{$roomId}",$fd,$userId);
        $redis -> set("room:fd:{$fd}",$roomId);
    }

    /**
     * 移除房间用户
     * removeRoomUser
     * @param $fd
     */
    public function removeRoomUser($fd) {
        $redis = app('Redis');
        $roomId = $this -> getRoomIdByFd($fd);
        $redis -> hDel("room:{$roomId}",$fd);
        $redis -> del("room:fd:{$fd}");
    }

    /**
     * 通过Fd获取UserId
     * getUserIdByFd
     * @param $fd
     * @return mixed
     */
    public function getUserIdByFd($fd) {
        $redis = app('Redis');
        $roomId = $this -> getRoomIdByFd($fd);
        return $redis -> hGet("room:{$roomId}",$fd);
    }

    /**
     * 通过Fd获取房间ID
     * getRoomIdByFd
     * @param $fd
     * @return string
     */
    public function getRoomIdByFd($fd) {
        $redis = app('Redis');
        return $redis -> get("room:fd:{$fd}");
    }



}
