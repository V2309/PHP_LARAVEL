<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\RoleMaster;
use App\Models\UserRolesMapping;
use Illuminate\Support\Str;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected function authenticated(Request $request, $user)
    {
        // Sinh token ngẫu nhiên
        $token = Str::random(60);
        
        // Cập nhật token vào cột auth_token
        $user->auth_token = $token;
        $user->save();
    
        // Lấy danh sách vai trò
        $roleNames = $user->roles->map(function($roleMapping) {
            return $roleMapping->role->rolename;
        })->toArray();
        // Kiểm tra vai trò và chuyển hướng tương ứng nếu là Admin
        if (in_array('Admin', $roleNames)) {
            return redirect()->route('dashboard');
        }
        // Nếu không phải Admin, chuyển hướng về trang chủ
        return redirect()->route('home');
    }
        
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
   protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

   
}
