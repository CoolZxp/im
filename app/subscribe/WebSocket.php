<?php
declare (strict_types = 1);

namespace app\subscribe;


use app\model\Room;
use app\model\User;
use app\model\UserMsg;
use Swoole\Server;

class WebSocket
{
    public $websocket;
    public $user;
    public $room;
    public $server;
    public $userMsg;

    public function __construct(\think\swoole\Websocket $websocket,Server $server,User $user,Room $room,UserMsg $userMsg)
    {
        $this -> websocket = $websocket;
        $this -> server = $server;
        $this -> user = $user;
        $this -> room = $room;
        $this -> userMsg = $userMsg;

    }

    public function onConnect($event) {
        $fd = $this -> websocket -> getSender();
        $token = $event -> get('token');
        $userId = $this -> user -> getUserTokenInfo($token);
        if ($userId === false) {
            $this -> websocket -> emit('user_error','非法操作');
//            $this -> websocket -> close();
        } else {
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

    public function onGetUserMsgList($event) {
        if ($this -> checkLogin()) {
            $msgList = $this -> userMsg -> getUserMsgList($this -> getUserId());
            var_dump($msgList);
            $this -> websocket -> emit('user_msg_list',$msgList);
        } else {
            $this -> websocket -> emit('user_error','非法操作');
        }
    }


//    public function onGetUserMsgInfo($event) {
//        if ($this -> checkLogin()) {
//            $msgInfo = $this -> userMsg -> getUserMsgInfo($event['id']);
//            $this -> websocket -> emit('UserMsgInfo',$msgInfo);
//        } else {
//            $this -> websocket -> emit('user_error','非法操作');
//        }
//    }
//



}
