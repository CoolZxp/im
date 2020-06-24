<?php
namespace app\model;

use think\facade\Db;
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
    //关联消息记录
    public function roomRecord()
    {
        return $this -> belongsTo(RoomRecord::class,'to_room_id','room_id') -> bind([
            'msg_content' => 'user_msg',
        ]);
    }

    /**
     * 获取器格式化消息时间
     * getMsgUpdateTimeAttr
     * @param $value
     * @return false|string
     */
    public function getMsgUpdateTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
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
        ]) -> order('msg_update_time','desc') -> select();
    }

    /**
     * 获取消息列表详细
     * getUserMsgInfoBySearch
     * @param $msgType
     * @param $userId
     * @param null $to_user_id
     * @param null $to_room_id
     * @return mixed
     */
    public function getUserMsgInfoBySearch($msgType,$userId,$to_user_id = null,$to_room_id = null)
    {
        switch ($msgType) {
            case 0:
                return $this -> with(['user','toUser']) -> where([
                    'user_id' => $userId,
                    'msg_type' => $msgType,
                    'to_user_id' => $to_user_id,
                ]) -> find();
                break;
            case 1:
                return $this -> with(['user','toRoom']) -> where([
                    'user_id' => $userId,
                    'msg_type' => $msgType,
                    'to_room_id' => $to_room_id,
                ]) -> find();
                break;
        }
        return null;
    }

    /**
     * 更新用户消息列表 用户之间
     * updateUserMsgByUser
     * @param $msg_type
     * @param $user_id
     * @param $msg_content
     * @param $to_user_id
     * @param null $msg_count
     */
    public function updateUserMsgByUser($msg_type,$user_id,$msg_content,$to_user_id,$msg_count = null) {
        $userMsgInfo = $this -> where(['msg_type' => $msg_type])
            -> where(['to_user_id' => $to_user_id])
            -> where(['user_id' => $user_id])
            -> find();
        //不存在则创建
        if (is_null($userMsgInfo)) {
            UserMsg::create([
                'msg_type' => $msg_type,
                'to_user_id' => $to_user_id,
                'user_id' => $user_id,
                'msg_count' => is_null($msg_count)?1:$msg_count,
                'msg_content' => $msg_content,
                'msg_update_time' => time()
            ]);
        } else {
            $userMsgInfo -> msg_count = is_null($msg_count)?Db::raw('msg_count + 1'):$msg_count;
            $userMsgInfo -> msg_content = $msg_content;
            $userMsgInfo -> msg_update_time = time();
            $userMsgInfo -> save();
        }

    }

    /**
     * 更新用户消息列表 聊天室
     * updateUserMsgByRoom
     * @param $msg_type
     * @param $msg_content
     * @param $to_room_id
     * @param null $msg_count
     */
    public function updateUserMsgByRoom($msg_type,$msg_content,$to_room_id,$msg_count = null) {
        //房间用户列表
        $roomUserList = RoomUser::field('user_id')
            -> where(['room_id' => $to_room_id])
            -> select();

        //有消息列表的用户
        $userMsgList = $this -> where(['msg_type' => $msg_type])
            -> where(['to_room_id' => $to_room_id])
            -> select();
        $diffUserId = $roomUserList -> diff($userMsgList,'user_id');
        //不存在则创建
        if (!$diffUserId -> isEmpty()) {
            $saveArr = [];
            foreach ($diffUserId as $item) {
                $saveArr[] = [
                    'msg_type' => $msg_type,
                    'to_room_id' => $to_room_id,
                    'user_id' => $item['user_id'],
                    'msg_count' => is_null($msg_count)?1:$msg_count,
                    'msg_content' => $msg_content,
                    'msg_update_time' => time()
                ];
            }
            $this -> saveAll($saveArr);
        }

        $updateArr = [];
        foreach ($userMsgList as $item) {
            $updateArr[] = [
                'id' => $item -> id,
                'msg_count' => is_null($msg_count)?Db::raw('msg_count + 1'):$msg_count,
                'msg_content' => $msg_content,
                'msg_update_time' => time(),
            ];
        };
        $this -> saveAll($updateArr);
    }


}
