<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;
use App\Models\Product;
use App\Models\Category;
use App\Models\Group; 

class ChatbotController extends Controller
{
    // Hiển thị giao diện chatbot
    public function index()
    {
        return view('chatbot'); // Trả về view chatbot.blade.php
    }

    // Xử lý API chatbot
    public function chat(Request $request)
    {
        $message = $request->input('message');

        if (!$message) {
            return response()->json(['error' => 'Message is required'], 400);
        }

        // Chuẩn hóa câu hỏi (không phân biệt hoa thường)
        $messageLower = strtolower($message);

        // Kiểm tra câu hỏi về số lượng sản phẩm
        if (strpos($messageLower, 'bao nhiêu sản phẩm') !== false || strpos($messageLower, 'số lượng sản phẩm') !== false) {
            $productCount = Product::count();
            $reply = "Hiện tại cửa hàng có {$productCount} sản phẩm.";
            return response()->json(['reply' => $reply]);
        }

        // Kiểm tra câu hỏi về số lượng loại sản phẩm
        if (strpos($messageLower, 'bao nhiêu loại sản phẩm') !== false || strpos($messageLower, 'số loại sản phẩm') !== false) {
            $categoryCount = Category::count();
            $reply = "Hiện tại cửa hàng có {$categoryCount} loại sản phẩm.";
            return response()->json(['reply' => $reply]);
        }

        // Kiểm tra câu hỏi về số lượng nhóm sản phẩm
        if (strpos($messageLower, 'bao nhiêu nhóm sản phẩm') !== false || strpos($messageLower, 'số nhóm sản phẩm') !== false) {
            $groupCount = Group::count();
            $reply = "Hiện tại cửa hàng có {$groupCount} nhóm sản phẩm.";
            return response()->json(['reply' => $reply]);
        }

   // Tìm sản phẩm theo tên (liệt kê tất cả sản phẩm khớp)
   $products = Product::where('ten_sanpham', 'LIKE', "%$message%")->get();
   if ($products->isNotEmpty()) {
       $reply = "Danh sách sản phẩm liên quan đến '$message':\n";
       foreach ($products as $product) {
           $reply .= "- {$product->ten_sanpham}: {$product->gia_moi} VND\n";
       }
       $reply .= "Bạn có muốn mua sản phẩm nào không?";
       return response()->json(['reply' => $reply]);
   }
        // Nếu không khớp, gọi OpenAI để trả lời chung
        $client = OpenAI::client(env('OPENAI_API_KEY'));
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Bạn là một trợ lý AI cho cửa hàng.'],
                ['role' => 'user', 'content' => $message]
            ]
        ]);

        return response()->json(['reply' => $response->choices[0]->message->content]);
    }
}