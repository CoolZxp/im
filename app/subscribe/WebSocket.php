<?php
declare (strict_types = 1);

namespace app\subscribe;

use app\model\Room;

class WebSocket
{
    public $websocket = null;
    public function __construct(\think\swoole\Websocket $websocket)
    {
        $this -> websocket = $websocket;
    }

    public function onConnect($event,Room $room,User $user) {
        $token = $event -> get('token');
        $roomId = $request -> get['roomId'];
        $userId = $userModel -> getUserTokenInfo($token);
        if ($userId === false) {
            $this -> websocket -> close($this -> websocket -> getSender());
        } else {
            $roomModel -> addRoomUser($roomId,$userId,$request -> fd);
            $roomInfo = RoomModel::get($roomId);
            $json['type'] = 'roomUserList';
            $json['data'] = $roomInfo -> room_user_list;
            $server -> push($request->fd, json_encode($json));

            $userInfo = $userModel -> field(['id','nick_name','user_face']) -> get($userId);
            $userList = $roomModel -> getRoomUserList($roomId);
            unset($userList[$request -> fd]);
            foreach ($userList as $k => $v) {
                $json['type'] = 'addRoomUser';
                $json['data'] = $userInfo;
                $server->push($k, json_encode($json));
            }

        }
        $this->websocket->emit("testcallback", ['aaaaa' => 1, 'getdata' => 11111]);
    }
    public function onClose($event) {
        $this->websocket->emit("testcallback", ['aaaaa' => 1, 'getdata' => 11111]);
    }

    public function onJoin($event) {

    }
    public function onTest($event)
    {
        var_dump($event);
        //回复客户端消息
        $this->websocket->emit("testcallback", ['aaaaa' => 1, 'getdata' => $event['asd']]);
        //不同于HTTP模式，这里可以进行多次发送
        $this->websocket->emit("testcallback", ['aaaaa' => 1, 'getdata' => $event['asd']]);
    }

}
