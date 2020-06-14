<?php
namespace app\index\model;

use redis\Redis;
use think\Model;


class RoomModel extends Model
{
    //关联User
    public function user()
    {
        return $this -> belongsTo('UserModel') -> bind([
            'user_name',
            'nick_name',
            'user_face',
        ]);
    }
    //关联RoomCate
    public function roomCate()
    {
        return $this -> belongsTo('RoomCateModel','cate_id') -> bind([
            'cate_name',
        ]);
    }


    /**
     * getRoomList 获取聊天室列表
     * @param null $cateId 分类Id
     * @return \think\Paginator
     */
    public function getRoomList($cateId = null) {
        $roomList = $this -> with(['user','roomCate']);
        if ($cateId != null) {
            $roomList -> where('cate_id',$cateId);
        }
        $pageConfig = [
            'query' => [
                'cateId'=> input('get.cateId')
            ]
        ];
        return $roomList -> paginate(16,false,$pageConfig) -> each(function ($item,$key) {
            $item['room_user_num'] = $this -> getRoomUserNum($item['id']);
            return $item;
        });
    }

    /**
     * getRoomUserNum 获取聊天室人数
     * @param $roomId
     * @return int
     */
    public function getRoomUserNum($roomId) {
        $redis = Redis::getInstance();
        $userNum = $redis -> hLen("room:{$roomId}");
        return $userNum;
    }

}
