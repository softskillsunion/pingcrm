<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // 引用 Auth facade

class EnsureSelf
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestedUser = $request->route('user');
        // 回傳訊息以利除錯
        // return response()->json([$requestedUser]);

        // 如果是owner或者讀取自己的页面才可進入
        if (auth::user()->owner || auth::user()->id === $requestedUser->id) {
            return $next($request);
        }

        // 否则重定向到自己的页面
        return redirect()->route('users.edit', ['user' => auth::user()->id]);
    }
}
