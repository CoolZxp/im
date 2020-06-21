<?php
declare (strict_types = 1);

namespace app\subscribe;


use app\model\Room;
use app\model\User;

class WebSocket
{
    public $websocket = null;
    public $user = null;
    public $room = null;
    public function __construct(\think\swoole\Websocket $websocket,User $user,Room $room)
    {
        $this -> websocket = $websocket;
        $this -> user = $user;
        $this -> room = $room;
    }

    public function onConnect($event) {
        $fd = $this -> websocket -> getSender();
        $token = $event -> get('token');

        $userId = $this -> user -> getUserTokenInfo($token);
        if ($userId === false) {
            $this -> websocket -> emit('error','非法操作');
            $this -> websocket -> close($fd);
        }
        $this -> user -> setUserFd($userId,$fd);
    }

    

//    public function onClose($event) {
//        $fd = $this -> websocket -> getSender();
//        $roomId = $this -> room -> getRoomIdByFd($fd);
//        $userId = $this -> room -> getUserIdByFd($fd);
//        $this -> room -> removeRoomUser($fd);
//        $this -> websocket -> leave($roomId);
//        $this -> websocket -> to($roomId) -> emit('removeRoomUser',[
//            'id' => $userId,
//        ]);
//    }
//
//    public function onJoin($event) {
//        $fd = $this -> websocket -> getSender();
//        $roomId = $event['roomId'];
//        $userId = $this -> room -> getUserIdByFd($fd);
//
//        $this -> room -> addRoomUser($roomId,$userId,$fd);
//        $this -> websocket -> join($roomId);
//        $roomUserList = $this -> room -> getRoomUserList($roomId);
//        $this -> websocket -> emit('roomUserList',$roomUserList);
//
//        $this -> websocket -> to($roomId) -> emit('addRoomUser',
//            $this -> user -> field(['id','nick_name','user_face']) -> find($userId)
//        );
//    }
//
//    public function onSendMsg($event) {
//        $fd = $this -> websocket -> getSender();
//        $roomId = $this -> room -> getRoomIdByFd($fd);
//        $userId = $this -> room -> getUserIdByFd($fd);
//
//        $userInfo = $this -> user -> field(['id','nick_name','user_face']) -> find($userId);
//        $this -> websocket -> to($roomId) -> emit('newRoomMsg',[
//            'userInfo' => $userInfo,
//            'time' => time(),
//            'msg' => $event['msg'],
//        ]);
//    }

}
