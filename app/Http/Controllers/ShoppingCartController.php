<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\TheOrder;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShoppingCartController extends Controller
{
    private $strCart = 'Carts';

    public function cartView()
    {
        $cart = $this->getCart();
        return view('pages.cartView', compact('cart'));
    }
    // hàm tự động tạo seesion_idid
    private function getCart()
    {
        // hàm tự độg tạo seesion_idid
        $sessionId = Session::getId();
        $user = Auth::user();

        if ($user) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        } else {
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        return $cart->load('items.product');
    }
    // thêm sản phẩm vào giỏ hàng
    public function orderNow($id_sanpham)
    {
        if (!$id_sanpham) {
            return response()->json(['status' => 0, 'message' => 'Product ID is required']);
        }

        $product = Product::find($id_sanpham);
        if (!$product) {
            return response()->json(['status' => 0, 'message' => 'Product not found']);
        }

        $cart = $this->getCart();
        $cartItem = $cart->items()->where('product_id', $id_sanpham)->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cart->items()->save($cartItem);
        } else {
            $cartItem = new CartItem([
                'product_id' => $id_sanpham,
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $cart->items()->save($cartItem);
        }

        return redirect()->route('cartView')->with('success', 'Product added to cart successfully');
    }
    // xóa sản phẩm khỏi giỏ hàng
    public function removeItem($id_sanpham)
    {
        if (!$id_sanpham) {
            return response()->json(['status' => 0, 'message' => '404']);
        }

        $cart = $this->getCart();
        $cartItem = $cart->items()->where('product_id', $id_sanpham)->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->route('cartView')->with('success', 'Product removed from cart successfully');
    }
    // xóa tất cả sản phẩm trong giỏ hàng
    public function clearCart()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        return redirect()->route('cartView')->with('success', 'Cart cleared successfully');
    }
    // cập nhật giỏ hàng
    public function updateCart(Request $request)
    {
        $quantities = $request->input('quantity', []);
        $cart = $this->getCart();

        foreach ($cart->items as $index => $item) {
            if (!isset($quantities[$index])) {
                continue;
            }

            $newQuantity = (int) $quantities[$index];
            $product = $item->product;

            if ($newQuantity > $product->so_luong) {
                return redirect()->route('cartView')->with('error', "Sản phẩm {$product->ten_sanpham} không đủ số lượng. Chỉ còn {$product->so_luong} sản phẩm có sẵn.");
            }

            if ($newQuantity <= 0) {
                $item->delete();
            } else {
                $item->quantity = $newQuantity;
                $item->save();
            }
        }

        return redirect()->route('cartView')->with('success', 'Cập nhật giỏ hàng thành công');
    }
    // thanh toán
    public function checkOut()
    {
        $cart = $this->getCart();
        if ($cart->items->isEmpty()) {
            return redirect()->route('cartView')->with('error', 'Giỏ hàng trống');
        }
        return view('pages.checkout', compact('cart'));
    }
    // xử lý đặt hàng
    public function processOrder(Request $request)
    {
        $cart = $this->getCart();
        if ($cart->items->isEmpty()) {
            return redirect()->route('cartView')->with('error', 'Giỏ hàng trống');
        }

        $request->validate([
            'tenkhachhang' => 'required|string|max:100',
            'diachi' => 'required|string|max:500',
            'sdt' => 'required|string|max:20',
            'email' => 'required|email|max:50',
            'hinhthucthanhtoan' => 'required|string|max:200',
        ]);

        $numberOfOrders = TheOrder::count();
        $order = new TheOrder();
        $order->tendonhang = 'Đơn hàng số ' . ($numberOfOrders + 1);
        $order->tenkhachhang = $request->input('tenkhachhang');
        $order->diachi = $request->input('diachi');
        $order->sdt = $request->input('sdt');
        $order->email = $request->input('email');
        $order->hinhthucthanhtoan = $request->input('hinhthucthanhtoan');
        $order->ngaydat = Carbon::now('Asia/Ho_Chi_Minh');
        $order->trangthai = 'Chờ xác nhận';

        if (Auth::check()) {
            $order->user_id = Auth::id();
        } else {
            $order->session_id = Session::getId();
        }

        $order->save();

        foreach ($cart->items as $item) {
            $orderDetail = new OrderDetail();
            $orderDetail->id_donhang = $order->id_donhang;
            $orderDetail->id_sanpham = $item->product->id_sanpham;
            $orderDetail->thanhtien = $item->product->gia_moi * $item->quantity;
            $orderDetail->soluong = $item->quantity;
            $orderDetail->save();

            $product = Product::find($item->product->id_sanpham);
            $product->so_luong -= $item->quantity;
            $product->save();
        }

        $cart->items()->delete();
        return redirect()->route('home')->with('success', 'Đặt hàng thành công');
    }

     // tích hợp thanh toán bằng ZaloPay
     public function payWithZaloPay(Request $request)
     {
        $cart = $this->getCart();
         $config = [
             "app_id" => 2554,
             "key1" => "sdngKKJmqEMzvh5QQcdD2A9XBSKUNaYn",
             "key2" => "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf",
             "endpoint" => "https://sb-openapi.zalopay.vn/v2/create",
             'query_status_url' => 'https://sb-openapi.zalopay.vn/v2/query',
             'refund_url' => 'https://sb-openapi.zalopay.vn/v2/refund',
             'query_refund_url' => 'https://sb-openapi.zalopay.vn/v2/query_refund',
         ];
 
         $embeddata = '{}'; // Merchant's data
         $items = '[]'; // Merchant's data
         $transID = rand(0,1000000); //Random trans id
         $order = [
             "app_id" => $config["app_id"],
             "app_time" => round(microtime(true) * 1000), // miliseconds
             "app_trans_id" => date("ymd") . "_" . $transID, // translation missing: vi.docs.shared.sample_code.comments.app_trans_id
             "app_user" => "user123",
             "item" => $items,
             "embed_data" => $embeddata,
            "amount"=> $cart->items->sum(function ($item) {
                 return $item->product->gia_moi * $item->quantity;
             }),
             "description" => "Lazada - Payment for the order #$transID",
             "bank_code" => "zalopayapp"
         ];
 
         $data = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . $order["amount"]
         . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
         $order["mac"] = hash_hmac("sha256", $data, $config["key1"]);    
 
         $context = stream_context_create([
             "http" => [
                 "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                 "method" => "POST",
                 "content" => http_build_query($order)
             ]
         ]);
 
         $resp = file_get_contents($config["endpoint"], false, $context);
         $result = json_decode($resp, true);
 
         return response()->json($result);
     }
     // Xử lý callback từ ZaloPay
     public function handleCallback(Request $request){
         $config = config('zalopay');
         $postdata = $request->getContent();
         $postdatajson = json_decode($postdata, true);
 
         $mac = hash_hmac("sha256", $postdatajson["data"], $config['key2']);
         $requestmac = $postdatajson["mac"];
 
         if (strcmp($mac, $requestmac) != 0) {
             return response()->json([
                 "return_code" => -1,
                 "return_message" => "mac not equal"
             ]);
         }
 
         $datajson = json_decode($postdatajson["data"], true);
         // Cập nhật trạng thái đơn hàng dựa trên $datajson['app_trans_id']
 
         return response()->json([
             "return_code" => 1,
             "return_message" => "success"
         ]);
     }
     // Hàm gọi API truy vấn trạng thái đơn hàng
     public function queryOrder(Request $request)
     {
         $config = config('zalopay');
         $app_trans_id = $request->app_trans_id; // Truyền từ client
 
         $data = $config['app_id'] . "|" . $app_trans_id . "|" . $config['key1'];
         $params = [
             "app_id" => $config['app_id'],
             "app_trans_id" => $app_trans_id,
             "mac" => hash_hmac("sha256", $data, $config['key1'])
         ];
 
         // $response = Http::post($config['query_status_url'], $params);
         // return response()->json($response->json());
     }
     // Hàm gọi API hoàn tiền
     public function refundOrder(Request $request)
     {
         $config = config('zalopay');
         $timestamp = round(microtime(true) * 1000);
 
         $params = [
             "app_id" => $config['app_id'],
             "m_refund_id" => date("ymd") . "_" . $config['app_id'] . "_" . $timestamp,
             "timestamp" => $timestamp,
             "zp_trans_id" => $request->zp_trans_id, // Truyền từ client
             "amount" => $request->amount, // Số tiền cần hoàn lại
             "description" => $request->description,
             "mac" => ''
         ];
 
         $data = implode('|', [
             $params['app_id'],
             $params['zp_trans_id'],
             $params['amount'],
             $params['description'],
             $params['timestamp']
         ]);
         $params['mac'] = hash_hmac("sha256", $data, $config['key1']);
 
         // $response = Http::post($config['refund_url'], $params);
         // return response()->json($response->json());
     }
     // Hàm gọi API truy vấn trạng thái hoàn tiền
     public function queryRefund(Request $request)
     {
         $config = config('zalopay');
         $timestamp = round(microtime(true) * 1000);
 
         $data = $config['app_id'] . "|" . $request->m_refund_id . "|" . $timestamp;
         $params = [
             "app_id" => $config['app_id'],
             "m_refund_id" => $request->m_refund_id,
             "timestamp" => $timestamp,
             "mac" => hash_hmac("sha256", $data, $config['key1'])
         ];
         // $response = Http::post($config['query_refund_url'], $params);
         // return response()->json($response->json());
     }
}