@extends('layouts.account')
@section('content')
<div class="container">
    <h2 class="chat-title">Chat Real-Time</h2>
    <div class="chat-container row">
        <!-- Danh sách user -->
        <div class="col-md-4 user-list">
            <h4>Danh sách người dùng</h4>
            <ul class="list-group">
                @foreach($users as $user)
                    <li class="list-group-item user-item" onclick="selectUser({{ $user->id }}, '{{ $user->name }}')">
                        {{ $user->name }} {{ $user->id == 1 ? '(Admin)' : '' }}
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Khu vực chat -->
        <div class="col-md-8 chat-area">
            <div id="chat-box" class="chat-box"></div>
            <form id="chat-form" method="POST" action="{{ route('chat.send') }}">
                @csrf
                <input type="hidden" name="receiver_id" id="receiver_id">
                <div class="form-group">
                    <textarea name="content" id="content" class="form-control chat-input" placeholder="Nhập tin nhắn..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary send-btn">Gửi</button>
            </form>
            <p class="chat-status">Đang chat với: <span id="receiver_name">Chọn người để chat</span></p>
        </div>
    </div>
</div>

<style>
    /* CSS cho giao diện chat */
    .chat-title {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .chat-container {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .user-list {
        padding-right: 15px;
    }

    .user-list h4 {
        color: #555;
        font-size: 18px;
        margin-bottom: 15px;
    }

    .list-group-item.user-item {
        cursor: pointer;
        transition: background 0.3s ease;
        border: none;
        margin-bottom: 5px;
        background: #fff;
        padding: 12px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        font-size: 16px
    }

    .list-group-item.user-item:hover {
        background: #e0f7fa;
    }

    .chat-area {
        padding-left: 15px;
    }

    .chat-box {
        height: 400px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.05);
    }

    .chat-box p {
        margin: 5px 0;
        font-family: 'Courier New', Courier, monospace;
        font-size: 16px;
        padding: 10px;
        border-radius: 5px;
        background: #f1f1f1;
        max-width: 70%;
        word-wrap: break-word;
    }

    .chat-box p strong {
        color: #007bff;
    }

    /* Tin nhắn của bạn (người gửi) */
    .chat-box p.your-message {
        background: #007bff;
        color: #fff;
        margin-left: auto;
        text-align: right;
    }

    .chat-input {
        resize: none;
        height: 60px;
        border-radius: 5px;
        border: 1px solid #ccc;
        padding: 10px;
        transition: border-color 0.3s ease;
    }

    .chat-input:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        outline: none;
    }

    .send-btn {
       
        padding: 10px;
        border-radius: 5px;
        background: #007bff;
        border: none;
        transition: background 0.3s ease;
    }

    .send-btn:hover {
        background: #0056b3;
    }

    .chat-status {
        font-style: italic;
        color: #666;
        margin-top: 10px;
    }

    .chat-status span {
        font-weight: bold;
        color: #333;
    }
</style>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Kết nối Pusher
    Pusher.logToConsole = true;
    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });
    var channel = pusher.subscribe('private-chat.' + {{ Auth::id() }});

    // Nhận tin nhắn real-time từ người khác
    channel.bind('App\\Events\\MessageSent', function(data) {
        var chatBox = document.getElementById('chat-box');
        var currentReceiverId = document.getElementById('receiver_id').value;

        if (data.message.sender_id != {{ Auth::id() }} && data.message.sender_id == currentReceiverId) {
            var senderName = document.getElementById('receiver_name').innerText;
            chatBox.innerHTML += '<p><strong>' + senderName + ':</strong> ' + data.message.content + '</p>';
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });

    // Chọn user để chat và tải lịch sử tin nhắn
    function selectUser(userId, userName) {
        document.getElementById('receiver_id').value = userId;
        document.getElementById('receiver_name').innerText = userName;

        fetch('/chat/messages?receiver_id=' + userId)
            .then(response => response.json())
            .then(messages => {
                var chatBox = document.getElementById('chat-box');
                chatBox.innerHTML = '';
                messages.forEach(msg => {
                    var senderName = msg.sender_id == {{ Auth::id() }} ? 'Bạn' : userName;
                    var messageClass = msg.sender_id == {{ Auth::id() }} ? 'your-message' : '';
                    chatBox.innerHTML += '<p class="' + messageClass + '"><strong>' + senderName + ':</strong> ' + msg.content + '</p>';
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }

    // Gửi tin nhắn qua AJAX và hiển thị ngay
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(form);
        var content = document.getElementById('content').value;

        // Hiển thị tin nhắn ngay lập tức ở phía người gửi
        var chatBox = document.getElementById('chat-box');
        chatBox.innerHTML += '<p class="your-message"><strong>Bạn:</strong> ' + content + '</p>';
        chatBox.scrollTop = chatBox.scrollHeight;

        // Gửi tin nhắn qua AJAX
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                form.reset();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            chatBox.lastElementChild.remove(); // Xóa tin nhắn nếu gửi thất bại
        });
    });
</script>
@endsection