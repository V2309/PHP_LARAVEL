<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class PhoneLoginController extends Controller
{
    public function showPhoneLoginForm()
    {
        return view('auth.phone_login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|regex:/^[0-9]{10,11}$/',
            'email' => 'required|email'
        ]);

        $phone_number = '+84' . ltrim($request->phone_number, '0');
        $email = $request->email;
        $otp = rand(100000, 999999);

        // Lưu OTP và email vào session
        session([
            'otp' => $otp,
            'phone_number' => $phone_number,
            'email' => $email,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Gửi OTP qua email
        Mail::raw("Mã OTP của bạn là: $otp. Hết hạn sau 10 phút.", function ($message) use ($email) {
            $message->to($email)->subject('Xác minh OTP');
        });

        return redirect()->route('phone.verify');
    }

    public function showVerifyForm()
    {
        $phone_number = session('phone_number');
        if (!$phone_number) {
            return redirect()->route('phone.login');
        }
        return view('auth.verify_otp', compact('phone_number'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'otp' => 'required|digits:6'
        ]);
    
        $phone_number = $request->phone_number;
        $otp = $request->otp;
    
        $sessionOtp = session('otp');
        $sessionPhone = session('phone_number');
        $sessionEmail = session('email');
        $expiresAt = session('otp_expires_at');
    
        if (!$sessionOtp || $sessionPhone !== $phone_number || $sessionOtp != $otp || now()->gt($expiresAt)) {
            return back()->withErrors(['otp' => 'Mã OTP không hợp lệ hoặc đã hết hạn']);
        }
    
        // Tìm user dựa trên phone_number hoặc email
        $user = User::where('phone_number', $phone_number)
                    ->orWhere('email', $sessionEmail)
                    ->first();
    
        if (!$user) {
            // Nếu không tìm thấy user, tạo mới
            $user = User::create([
                'name' => 'User_' . Str::random(6),
                'phone_number' => $phone_number,
                'email' => $sessionEmail,
                'password' => bcrypt(Str::random(10)),
            ]);
        } else {
            // Nếu user đã tồn tại, cập nhật phone_number nếu cần
            if ($user->phone_number != $phone_number) {
                $user->phone_number = $phone_number;
                $user->save();
            }
        }
    
        Auth::login($user);
        session()->forget(['otp', 'phone_number', 'email', 'otp_expires_at']);
    
        $roleNames = $user->roles->map(function($roleMapping) {
            return $roleMapping->role->rolename;
        })->toArray();
    
        if (in_array('Admin', $roleNames)) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('home');
    }
}