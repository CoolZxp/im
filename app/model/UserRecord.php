<?php
namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;


class UserRecord extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    //关联User
    public function fromUser()
    {
        return $this -> belongsTo(User::class,'from_user_id') -> bind([
            'user_name',
            'nick_name',
            'user_face',
        ]);
    }
    //关联User
    public function toUser()
    {
        return $this -> belongsTo(User::class,'to_user_id') -> bind([
            'to_user_name' => 'user_name',
            'to_nick_name' => 'nick_name',
            'to_user_face' => 'user_face',
        ]);
    }

    /**
     * 获取好友消息记录
     * getUserRecordList
     * @param $fromUserId
     * @param $toUserId
     * @param null $msg_id
     * @return \think\Collection
     */
    public function getUserRecordList($fromUserId,$toUserId,$msg_id = null) {
        $roomRecordList = $this -> with(['fromUser','toUser'])
            -> where(function ($query) use($fromUserId,$toUserId){
                $query -> whereOr([
                    [
                        ['from_user_id', '=',$fromUserId],
                        ['to_user_id' , '=',$toUserId]
                    ],[
                        ['from_user_id','=', $toUserId],
                        ['to_user_id', '=',$fromUserId]
                    ]
                ]);
            })
            -> order('id','desc')
            -> limit(20);
        if (!is_null($msg_id)) {
            $roomRecordList-> where('id','<',$msg_id);
        }
        return $roomRecordList -> select() -> reverse();
    }


    /**
     * 获取好友消息单个记录
     * getUserRecordInfo
     * @param $id
     * @return array|Model|null
     */
    public function getUserRecordInfo($id) {
        return $this -> with(['fromUser','toUser'])
            -> find($id);
    }

}
