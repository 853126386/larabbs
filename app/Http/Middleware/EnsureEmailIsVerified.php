<?php

namespace App\Http\Middleware;

use Closure;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user()&& //已登入用户为true
            !$request->user()->hasVerifiedEmail()&& //没有进行邮箱认证则为true
            !$request->is('email/*','logout') //访问链接不含有'email/*','logout' 字符为true
        ){
            // 根据客户端返回对应的内容
            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : redirect()->route('verification.notice');

        }
        return $next($request);
    }
}
