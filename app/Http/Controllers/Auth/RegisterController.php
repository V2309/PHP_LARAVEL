<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login'; // Hoặc dùng route('login')
    /**
     * Create a new controller instance.
     *
     * @return void
     */
  
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // Validate dữ liệu đầu vào
        // Sử dụng Validator để kiểm tra dữ liệu
        // Nếu không hợp lệ, sẽ trả về lỗi
        // Nếu hợp lệ, sẽ tiếp tục thực hiện phương thức create
        
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            // thông báo lỗi nếu không hợp lệ
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // mật khẩu phải có ít nhất 8 ký tự, xác nhận mật khẩu
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
       
    }
    // Ghi đè phương thức register
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        // Không đăng nhập tự động, chỉ chuyển hướng về /login
        return redirect($this->redirectTo);
    }
    
}
