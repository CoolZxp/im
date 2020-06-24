<?php
namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;


class UserFriend extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    /**
     * 判断用户是否为好友
     * isUserInFriend
     * @param $fromUserId
     * @param $toUserId
     * @return bool
     */
    public function isUserInFriend($fromUserId,$toUserId) {
        return $this -> where(function ($query) use($fromUserId,$toUserId){
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
            -> count() > 0;
    }

}
