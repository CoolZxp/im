<?php
namespace app\http;

use app\index\model\RoomModel;
use app\index\model\UserModel;

class SwooleSocket
{
    public function onOpen ($server, $request) {
        $roomModel = new RoomModel();
        $userModel = new UserModel();
        $token = $request -> get['token'];
        $roomId = $request -> get['roomId'];
        $userId = $userModel -> getUserTokenInfo($token);
        if ($userId === false) {
            $server -> disconnect($request -> fd,1000,'非法操作');
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
    }

    public function onMessage ($server, $frame) {
        $roomModel = new RoomModel();
        $userModel = new UserModel();

        $roomId = $roomModel -> getRoomIdByFd($frame -> fd);
        $userId = $roomModel -> getUserIdByFd($frame -> fd);
        $userInfo = $userModel -> field(['id','nick_name','user_face']) -> get($userId);

        $userList = $roomModel -> getRoomUserList($roomId);
        unset($userList[$frame->fd]);
        foreach ($userList as $k => $v) {
            $json['type'] = 'newRoomMsg';
            $json['data'] = [
                'userInfo' => $userInfo,
                'time' => time(),
                'msg' => $frame->data,
            ];
            $server->push($k, json_encode($json));
        }
    }

    public function onClose ($server, $fd) {
        $roomModel = new RoomModel();
        $roomId = $roomModel -> getRoomIdByFd($fd);
        $userId = $roomModel -> getUserIdByFd($fd);
        $userList = $roomModel -> getRoomUserList($roomId);
        unset($userList[$fd]);
        foreach ($userList as $k => $v) {
            $json['type'] = 'removeRoomUser';
            $json['data'] = [
                'id' => $userId,
            ];
            $server->push($k, json_encode($json));
        }
        $roomModel -> removeRoomUser($fd);
    }


}