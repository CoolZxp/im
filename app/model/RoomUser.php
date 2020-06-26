<?php
namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;


class RoomUser extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    //关联User模型
    public function user() {
        return $this -> belongsTo(User::class,'user_id') -> bind([
            'user_name',
            'nick_name',
            'user_face',
        ]);
    }

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

    /**
     * 获取房间内用户信息 分页
     * getRoomUserByPage
     * @param $roomId
     * @param null $page
     * @return \think\Collection
     */
    public function getRoomUserByPage($roomId,$page = null) {
        $pageSize = 20;
        return $this -> with('user')
            -> where(['room_id' => $roomId])
            -> page($page,$pageSize)
            -> select();
    }

    /**
     * 获取房间用户人数
     * getRoomUserCount
     * @param $roomId
     * @return int
     */
    public function getRoomUserCount($roomId) {
        return $this -> where(['room_id' => $roomId]) -> count();
    }

}
