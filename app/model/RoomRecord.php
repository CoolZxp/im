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

    /**
     * 获取房间消息记录
     * getRoomRecordList
     * @param $roomId
     * @param $page
     * @return \think\Collection
     */
    public function getRoomRecordList($roomId,$msg_id = null) {
        $roomRecordList = $this -> with(['user'])
            -> where('room_id',$roomId)
            -> order('id','desc')
            -> limit(20);
        if (!is_null($msg_id)) {
            $roomRecordList-> where('id','<',$msg_id);
        }
        return $roomRecordList -> select() -> reverse();
    }


    /**
     * 获取房间消息单个记录
     * getRoomRecordInfo
     * @param $id
     * @return array|Model|null
     */
    public function getRoomRecordInfo($id) {
        return $this -> with(['user'])
            -> find($id);
    }

}
