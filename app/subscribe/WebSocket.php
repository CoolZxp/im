<?php
declare (strict_types = 1);

namespace app\subscribe;


use app\model\Room;
use app\model\RoomRecord;
use app\model\RoomUser;
use app\model\User;
use app\model\UserFriend;
use app\model\UserMsg;
use app\model\UserRecord;
use Swoole\Server;

class WebSocket
{
    public $websocket;
    public $user;
    public $room;
    public $server;
    public $userMsg;
    public $userRecord;
    public $roomRecord;
    public $roomUser;
    public $userFriend;

    public function __construct(
        \think\swoole\Websocket $websocket,
        Server $server,
        User $user,
        Room $room,
        UserMsg $userMsg,
        RoomRecord $roomRecord,
        RoomUser $roomUser,
        UserRecord $userRecord,
        UserFriend $userFriend
    ) {
        $this -> websocket = $websocket;
        $this -> server = $server;
        $this -> user = $user;
        $this -> room = $room;
        $this -> userMsg = $userMsg;
        $this -> roomRecord = $roomRecord;
        $this -> roomUser = $roomUser;
        $this -> userRecord = $userRecord;
        $this -> userFriend = $userFriend;
    }


    public function onConnect($event) {
//        var_dump($event);
        $fd = $this -> websocket -> getSender();
        $token = $event -> get('token');
        $uuid = $event -> get('uuid');
        $userId = $this -> user -> getUserTokenInfo($token);
        if ($userId === false) {
            $this -> websocket -> emit('user_error','非法操作');
//            $this -> websocket -> close();
        } else {
            $oldFd = $this -> user -> getFdByUserId($userId);
            if ($oldFd) {

                $this -> websocket
                    -> to($oldFd)
                    -> emit('user_login_error',[
                        'msg' => '账号已在别处登录! ' . date('Y-m-d H:i:s'),
                        'uuid' => $uuid,
                    ]);
                $this -> user -> removeUserIdByFd($oldFd);
            }
            $this -> user -> setUserFd($userId,$fd);

        }
    }


    public function onClose($event) {
        $fd = $this -> websocket -> getSender();
        $this -> user -> removeUserIdByFd($fd);
    }

    /**
     * 检测是否通过权限验证
     * checkLogin
     * @param null $fd
     * @return bool
     */
    protected function checkLogin($fd = null) {
        if (is_null($fd)) {
            $fd = $this -> websocket -> getSender();
        }
        return is_null($this -> user -> getUserIdByFd($fd))?false:true;
    }

    /**
     * 获取用户ID
     * getUserId
     * @param null $fd
     * @return mixed
     */
    protected function getUserId($fd = null) {
        if (is_null($fd)) {
            $fd = $this -> websocket -> getSender();
        }
        return $this -> user -> getUserIdByFd($fd);
    }

    /**
     * 获取消息列表
     * onGetUserMsgList
     * @param $event
     */
    public function onGetUserMsgList($event) {
        if ($this -> checkLogin()) {
            $msgList = $this -> userMsg -> getUserMsgList($this -> getUserId());
            $this -> websocket -> emit('user_msg_list',$msgList);
        } else {
            $this -> websocket -> emit('user_error','非法操作');
        }
    }

    /**
     * 获取消息记录
     * onGetUserMsgRecordList
     * @param $event
     */
    public function onGetUserMsgRecordList($event) {
        if ($this -> checkLogin()) {
            if (empty($event['id']) || (empty($event['msgType']) && $event['msgType'] !== 0)) {
                $this -> websocket -> emit('error','未知参数');
            } else {
                if (empty($event['msgId'])) {
                    $event['msgId'] = null;
                }
                $userId = $this -> getUserId();
                switch ($event['msgType']) {
                    case 0:
                        $recordList = $this -> userRecord
                            -> getUserRecordList($userId,$event['id'],$event['msgId'])
                            -> each(function ($item) use($userId){
                                if ($item['from_user_id'] == $userId) {
                                    $item['is_self'] = true;
                                } else {
                                    $item['is_self'] = false;
                                }
                                return $item;
                            });
                        break;
                    case 1:
                        $recordList = $this -> roomRecord
                            -> getRoomRecordList($event['id'],$event['msgId'])
                            -> each(function ($item) use($userId){
                                if ($item['user_id'] == $userId) {
                                    $item['is_self'] = true;
                                } else {
                                    $item['is_self'] = false;
                                }
                                return $item;
                            });
                        break;
                }
                $this -> websocket -> emit('user_msg_record_list',$recordList);
            }
        } else {
            $this -> websocket -> emit('user_error','非法操作');
        }
    }


    /**
     * 加入Websocket房间
     * joinWebsocketRoom
     * @param $event
     */
    public function onJoinWebsocketRoom($event)
    {
        if ($this->checkLogin()) {
            if (empty($event['roomId'])) {
                $this -> websocket -> emit('error','未知参数');
            } else {
                //判断用户是否加入聊天室
                if (!$this -> roomUser -> isUserInRoom($this -> getUserId(),$event['roomId'])) {
                    $this -> websocket -> emit('error','非法操作');
                    return;
                }
                $this->websocket->join("room_{$event['roomId']}");
            }
        } else {
            $this->websocket->emit('user_error', '非法操作');
        }
    }
    /**
     * 离开Websocket房间
     * leaveWebsocketRoom
     * @param $event
     */
    public function onLeaveWebsocketRoom($event)
    {
        if ($this->checkLogin()) {
            if (empty($event['roomId'])) {
                $this -> websocket -> emit('error','未知参数');
            } else {
                //判断用户是否加入聊天室
                if (!$this -> roomUser -> isUserInRoom($this -> getUserId(),$event['roomId'])) {
                    $this -> websocket -> emit('error','非法操作');
                    return;
                }
                $this->websocket->leave("room_{$event['roomId']}");
            }
        } else {
            $this->websocket->emit('user_error', '非法操作');
        }
    }

    /**
     * 获取用户列表单个
     * onGetUserMsgInfo
     * @param $event
     */
    public function onGetUserMsgInfo($event)
    {
        if ($this->checkLogin()) {
            if (empty($event['mixedId']) ||  (empty($event['msgType']) && $event['msgType'] !== 0)) {
                $this -> websocket -> emit('error','未知参数');
            } else {
                $userMsgInfo = [];

                switch ($event['msgType']) {
                    case 0:
                        $userMsgInfo = $this -> userMsg -> getUserMsgInfoBySearch($event['msgType'],$this -> getUserId(),$event['mixedId']);
                        break;
                    case 1:
                        $userMsgInfo = $this -> userMsg -> getUserMsgInfoBySearch($event['msgType'],$this -> getUserId(),null,$event['mixedId']);
                        break;
                }
                $this -> websocket -> emit('get_user_msg_info',$userMsgInfo);
            }
        } else {
            $this->websocket->emit('user_error', '非法操作');
        }
    }

    /**
     * 发送消息
     * onSendMsg
     * @param $event
     */
    public function onSendMsg($event)  {

        if ($this -> checkLogin()) {
            if ((empty($event['msg_type']) && $event['msg_type'] !== 0)|| empty($event['msg']) || empty($event['uuid'])) {
                $this -> websocket -> emit('error','未知参数');
            } else {
                switch ($event['msg_type']) {
                    case 0:
                        //向聊天室发送消息
                        //判断用户是否加入房间
                        if (!$this -> userFriend -> isUserInFriend($this -> getUserId(),$event['to_user_id'])) {
                            $this -> websocket -> emit('error','非法操作');
                            return;
                        }

                        //添加消息记录
                        $userRecordInfo = UserRecord::create([
                            'from_user_id' => $this -> getUserId(),
                            'to_user_id' => $event['to_user_id'],
                            'user_msg' => $event['msg']
                        ]);

                        //更新两个人消息列表
                        //自己
                        $this -> userMsg -> updateUserMsgByUser(
                            $event['msg_type'],
                            $this -> getUserId(),
                            $event['msg'],
                            $event['to_user_id']
                        );
                        //对方
                        $this -> userMsg -> updateUserMsgByUser(
                            $event['msg_type'],
                            $event['to_user_id'],
                            $event['msg'],
                            $this -> getUserId()
                        );

                        //推送消息
                        $toArr = [$this -> websocket -> getSender()];
                        $toFd = $this -> user -> getFdByUserId($event['to_user_id']);
                        if ($toFd) {
                            $toArr[] = $toFd;
                        }
                        $this -> websocket
                            -> to($toArr)
                            -> emit('new_msg',[
                                'msg_type' => $event['msg_type'],
                                'uuid' => $event['uuid'],
                                'record_info' =>  $this -> userRecord -> getUserRecordInfo($userRecordInfo -> id)
                            ]);
                        break;
                    case 1:
                        //向聊天室发送消息
                        //判断用户是否加入房间
                        if (!$this -> roomUser -> isUserInRoom($this -> getUserId(),$event['to_room_id'])) {
                            $this -> websocket -> emit('error','非法操作');
                            return;
                        }

                        //添加消息记录
                        $roomRecordInfo = RoomRecord::create([
                            'user_id' => $this -> getUserId(),
                            'room_id' => $event['to_room_id'],
                            'user_msg' => $event['msg']
                        ]);
                        //更新消息列表
                        //全体
                        $this -> userMsg -> updateUserMsgByRoom(
                            $event['msg_type'],
                            $event['msg'],
                            $event['to_room_id']
                        );
                        //向所有房间在线用户推送消息ID
                        $this -> websocket
                            -> to($this -> roomUser -> getRoomUserFd($event['to_room_id']))
                            -> emit('new_msg',[
                                'msg_type' => $event['msg_type'],
                                'uuid' => $event['uuid'],
                                'record_info' => $this -> roomRecord -> getRoomRecordInfo($roomRecordInfo -> id)
                            ]);
                        break;
                }
            }
        } else {
            $this -> websocket -> emit('user_error','非法操作');
        }
    }


    /**
     * 已读消息
     * activeUserMsg
     * @param $event
     */
    public function onActiveUserMsg($event) {
        if ($this -> checkLogin()) {
            if (empty($event['id'])) {
                $this -> websocket -> emit('error','未知参数');
            } else {
                UserMsg::update([
                    'msg_count' => 0,
                ],[
                    'id' => $event['id'],
                ]);
            }
        } else {
            $this -> websocket -> emit('user_error','非法操作');
        }
    }


}
