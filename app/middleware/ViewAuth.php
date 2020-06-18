<?php
declare (strict_types = 1);

namespace app\middleware;
use app\model\User;

class ViewAuth
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
        if (!empty($token)) {
            $userModel = new User;
            $userId = $userModel -> getUserTokenInfo($token);
            if ($userId !== false) {
                $request -> userId = $userId;
                $request -> token = $token;
                $userInfo = $userModel -> getUserInfo($userId);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
        return $next($request);
    }
}
