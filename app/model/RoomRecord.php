<?php
namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;


class RoomRecord extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    //关联User
    public function user()
    {
        return $this -> belongsTo(User::class,'user_id') -> bind([
            'user_name',
            'nick_name',
            'user_face',
        ]);
    }

//    /**
//     * 获取房间消息记录以及用户信息
//     * getRoomRecordList
//     * @param $roomId
//     * @return \think\Collection
//     */
//    public function getRoomRecordList($roomId) {
//        return $this -> with(['user'])
//            -> where(['room_id' => $roomId])
//            -> order('update_time','desc')
//            -> limit(20)
//            -> select();
//    }

}
