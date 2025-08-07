<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class SendMailController extends Controller
{
    public function sendSupportEmail(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'gender' => 'required|in:Anh,Chị',
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9]{10,11}$/',
            'email' => 'required|email',
        ]);

        $data = [
            'content' => $request->content,
            'gender' => $request->gender,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ];

        // Gửi email tới admin hoặc email hỗ trợ
        Mail::raw(
            "Họ tên: {$data['gender']} {$data['name']}\n" .
            "Số điện thoại: {$data['phone']}\n" .
            "Email: {$data['email']}\n" .
            "Nội dung: {$data['content']}",
            function ($message) use ($data) {
                $message->to('vanduongcr6@gmail.com') // Thay bằng email hỗ trợ thực tế
                        ->from($data['email'], "{$data['gender']} {$data['name']}")
                        ->subject('Yêu cầu hỗ trợ từ GoFood');
            }
        );

        return redirect()->back()->with('success', 'Góp ý của bạn đã được gửi thành công! Chúng tôi sẽ liên hệ sớm nhất.');
    }
}
