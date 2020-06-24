<?php
namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;


class RoomUser extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    /**
     * 获取用户是否加入聊天室
     * isUserInRoom
     * @param $userId
     * @param $roomId
     * @return bool
     */
    public function isUserInRoom($userId,$roomId) {
        return $this -> where('user_id',$userId)
            -> where('room_id',$roomId)
            -> count() > 0;
    }

    /**
     * 获取房间所有在线用户Fd
     * getRoomUserFd
     * @param $roomId
     * @return array
     */
    public function getRoomUserFd($roomId) {
        $user = new User();
        $userFdList = [];
        $userIdList = $this -> field('user_id') ->  where('room_id',$roomId) -> select();
        foreach ($userIdList as $item) {
            $userFd = $user -> getFdByUserId($item -> user_id);
            if (!is_null($userFd)) {
                $userFdList[] = $userFd;
            }
        }
        return $userFdList;
    }
}
