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
    public function handle($request, \Closure $next,$ifLogin = false)
    {
        $token = cookie('token');
        if (!empty($token)) {
            $userModel = new User;
            $userId = $userModel -> getUserTokenInfo($token);
            if ($userId !== false) {
                if (!$ifLogin) {
                    $request->userId = $userId;
                    $request->token = $token;
                } else {
                    return redirect(url('/') -> build());
                }
            } else {
                if (!$ifLogin) {
                    return redirect(url('User/login') -> build());
                }
            }
        } else {
            if (!$ifLogin) {
                return redirect(url('User/login') -> build());
            }
        }

        return $next($request);
    }
}
