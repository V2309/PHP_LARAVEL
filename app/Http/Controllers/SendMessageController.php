<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use App\Events\MessageSent;
use App\Events\ChatMessageSent;
class SendMessageController extends Controller
{
    public function showChat()
    {
        $users = User::where('id', '!=', Auth::id())->get(); // Lấy danh sách user để chọn người chat
        return view('pages.chat', compact('users'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        // Broadcast tin nhắn tới cả sender và receiver
        event(new MessageSent($message));

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function getMessages(Request $request)
    {
        $receiverId = $request->query('receiver_id');
        $messages = Message::where(function ($query) use ($receiverId) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }
}
