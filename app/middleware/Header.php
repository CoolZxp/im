<?php
declare (strict_types = 1);

namespace app\middleware;
use think\facade\View;
use app\model\User;

class Header
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $token = cookie('token');
        View::assign('isLogin',false);
        if (!empty($token)) {
            $userModel = new User;
            $userId = $userModel -> getUserTokenInfo($token);
            if ($userId !== false) {
                $userInfo = $userModel -> getUserInfo($userId);
                View::assign('isLogin',true);
                View::assign('userFace',$userInfo['user_face']);
                View::assign('nickName',$userInfo['nick_name']);
            }
        }
        return $next($request);
    }
}
