<?php
declare (strict_types = 1);

namespace app\middleware;
use app\model\User;
class Auth
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
        $token = $request -> header('token');
        if (!empty($token)) {
            $userModel = new User;
            $userId = $userModel -> getUserTokenInfo($token);
            if ($userId !== false) {
                $request -> userId = $userId;
                $request -> token = $token;
            } else {
                return generate_json(ERROR_AUTH);
            }
        } else {
            return generate_json(ERROR_AUTH);
        }
        return $next($request);
    }
}
