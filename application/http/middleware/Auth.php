<?php

namespace app\http\middleware;
use app\index\model\UserModel;
class Auth
{
    public function handle($request, \Closure $next)
    {
        $token = $request -> header('token');
        if (!empty($token)) {
            $userModel = new UserModel;
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
