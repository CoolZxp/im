<?php
namespace app\index\model;

use think\facade\Cache;
use think\Model;


class RoomModel extends Model
{

    public function user()
    {
        return $this -> belongsTo('UserModel') -> bind([
            'user_name',
            'nick_name',
            'user_face',
        ]);
    }

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
        return $roomList -> paginate(1);
    }
}
