<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // Nếu chưa đăng nhập, chuyển về login
        }

        $user = Auth::user();
        $roleNames = $user->roles->map(function ($roleMapping) {
            return $roleMapping->role->rolename;
        })->toArray();

        if (!in_array('Admin', $roleNames)) {
            // Đăng xuất người dùng và chuyển về login nếu không phải Admin
            Auth::logout();
            return redirect()->route('login')->with('error', 'Bạn không có quyền truy cập trang này, vui lòng đăng nhập bằng tài khoản Admin.');
        }

        return $next($request); // Nếu là Admin, cho phép tiếp tục
    }
}
