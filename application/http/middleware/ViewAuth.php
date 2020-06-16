<?php

namespace app\http\middleware;
use app\index\model\UserModel;
class ViewAuth
{
    public function handle($request, \Closure $next)
    {
        $token = cookie('token');
        if (!empty($token)) {
            $userModel = new UserModel;
            $userId = $userModel -> getUserTokenInfo($token);
            if ($userId !== false) {
                $request -> userId = $userId;
                $request -> token = $token;
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
        return $next($request);
    }
}
