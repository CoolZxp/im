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
        return $this -> all();
    }

}
