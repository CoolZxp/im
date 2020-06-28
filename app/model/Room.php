<?php
namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;


class Room extends Model
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
    //关联RoomCate
    public function roomCate()
    {
        return $this -> belongsTo(RoomCate::class,'cate_id') -> bind([
            'cate_name',
        ]);
    }

    //关联RoomUser
    public function roomUser() {
        return $this -> hasMany(RoomUser::class,'room_id');
    }



}
