<?php
namespace app\model;

use think\Model;


class RoomCate extends Model
{
    /**
     * getRoomCateList 获取聊天室分类列表
     * @return \think\Collection
     */
    public function getRoomCateList() {
        $roomCateList = $this -> field(['id','cate_name']) -> select();
        $roomCateList -> unshift([
            'id' => 0,
            'cate_name' => '全部',
        ]);
        return $roomCateList;
    }

}
