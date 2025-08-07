<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot Laravel</title>
    <style>
        #chatbox { width: 100%; max-width: 500px; margin: auto; border: 1px solid #ccc; padding: 10px; }
        .message { padding: 5px; margin: 5px 0; border-radius: 5px; }
        .user { background: #007bff; color: white; text-align: right; }
        .bot { background: #f1f1f1; color: black; text-align: left; }
    </style>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    #chat-container {
        width: 350px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    #chatbox {
        height: 400px;
        overflow-y: auto;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }
    .message {
        padding: 8px;
        margin: 5px 0;
        border-radius: 5px;
        max-width: 80%;
        word-wrap: break-word;
    }
    .user {
        background: #007bff;
        color: white;
        text-align: right;
        align-self: flex-end;
    }
    .bot {
        background: #f1f1f1;
        color: black;
        text-align: left;
    }
    #input-container {
        display: flex;
        padding: 10px;
    }
    #userMessage {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
        outline: none;
    }
    button {
        padding: 8px 15px;
        border: none;
        background: #007bff;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 5px;
    }
</style>

<body>
    <div id="chat-container">
        <div id="chatbox"></div>
        <div id="input-container">
            <input type="text" id="userMessage" placeholder="Nhập tin nhắn..." />
            <button onclick="sendMessage()">Gửi</button>
        </div>
    </div>

    <script>
    function sendMessage() {
    let message = document.getElementById("userMessage").value.trim();
    if (!message) return;

    let chatBox = document.getElementById("chatbox");

    // Hiển thị tin nhắn của người dùng
    let userMessage = document.createElement("div");
    userMessage.classList.add("message", "user");
    userMessage.textContent = message;
    chatBox.appendChild(userMessage);
    
    document.getElementById("userMessage").value = "";
    chatBox.scrollTop = chatBox.scrollHeight; // Cuộn xuống tin nhắn mới nhất

    // Gửi yêu cầu đến Laravel
    fetch("/chatbot", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    },
    body: JSON.stringify({ message: message })
})
.then(response => response.json())
.then(data => {
    let botMessage = document.createElement("div");
    botMessage.classList.add("message", "bot");
    botMessage.innerHTML = data.reply;
    chatBox.appendChild(botMessage);
    chatBox.scrollTop = chatBox.scrollHeight;
});
}
    </script>
</body>
</html>
