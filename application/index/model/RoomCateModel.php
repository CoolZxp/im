<?php
namespace app\index\model;

use think\Model;


class RoomCateModel extends Model
{
    /**
     * getRoomCateList 获取聊天室分类列表
     * @return mixed
     */
    public function getRoomCateList() {
        $roomCateList = $this -> field(['id','cate_name']) -> all();
        $roomCateList -> unshift([
            'id' => 0,
            'cate_name' => '全部',
        ]);
        return $roomCateList;
    }

}
