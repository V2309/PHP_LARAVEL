<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\TheOrder;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ShoppingCartController extends Controller
{
    //
    // khởi tạo biến strCart để lưu trữ giỏ hàng
    private $strCart = 'Carts';

    // hàm trả về trang giỏ hàng
    public function cartView()  {
        return view('pages.cartView');
    }

    // thêm sản phẩm vào giỏ hàng
    public function orderNow($id_sanpham)
    {
        // kiểm tra xem id sản phẩm có tồn tại không
        if (!$id_sanpham) {
            return response()->json(['status' => 0, 'message' => 'Product ID is required']);
        }
        // lấy giỏ hàng từ session
        $cart = Session::get($this->strCart, []);
        $product = Product::find($id_sanpham);
        if (!$product) {
            return response()->json(['status' => 0, 'message' => 'Product not found']);
        }
        // kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
        $existingIndex = $this->isExistingCheck($id_sanpham);
        if ($existingIndex === -1) {
            $cart[] = new Cart($product, 1);
        } else {
            $cart[$existingIndex]->quantity++;
        }
        // lưu giỏ hàng vào session
        Session::put($this->strCart, $cart);
        return redirect()->route('cartView')->with('success', 'Product added to cart successfully');
    }

    // hàm kiểm tra sản phẩm đã tồn tại trong giỏ hàng chưa
    private function isExistingCheck($id_sanpham)
    {
        // lấy giỏ hàng từ session
        $cart = Session::get($this->strCart, []);
        // duyệt qua từng sản phẩm trong giỏ hàng
        // nếu sản phẩm đã tồn tại thì trả về index của sản phẩm đó trong giỏ hàng
        foreach ($cart as $index => $item) {
            if ($item->product->id_sanpham == $id_sanpham) {
                return $index;
            }
        }
        return -1;
    }
    // hàm xóa sản phẩm khỏi giỏ hàng
    public function removeItem($id_sanpham){
       // kiểm tra xem id sản phẩm có tồn tại không
       // nếu không tồn tại thì trả về thông báo lỗi
        if (!$id_sanpham) {
            return response()->json(['status' => 0, 'message' => '404']);
        }
        // lấy giỏ hàng từ session
        $cart = Session::get($this->strCart, []);
        $existingIndex = $this->isExistingCheck($id_sanpham);
        // kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
        // nếu sản phẩm đã tồn tại thì xóa sản phẩm đó khỏi giỏ hàng
        if ($existingIndex !== -1) {
            unset($cart[$existingIndex]);
            $cart = array_values($cart); // reset index
        }
        // kieemr tra xem giỏ hàng có rỗng không
        // nếu rỗng thì xóa giỏ hàng khỏi session
        if(empty($cart)){
            Session::forget($this->strCart);
        }
        // nếu không rỗng thì lưu giỏ hàng vào session
        else{
            Session::put($this->strCart, $cart);
        }
        return redirect()->route('cartView')->with('success', 'Product removed from cart successfully');

    }
    // xóa hết sản phẩm khỏi giỏ hàng
    public function clearCart(){
        // xóa giỏ hàng khỏi session
        Session::forget($this->strCart);
        return redirect()->route('cartView')->with('success', 'Cart cleared successfully');
    }
    
    // cập nhật giỏ hàng
    public function updateCart(Request $request){
        
        $quanties = $request->input('quantity'); // lấy danh sách số lượng sản phẩm từ form
        $cart = Session::get($this->strCart, []); // lấy giỏ hàng từ session
        // duyệt qua từng sản phẩm trong giỏ hàng
        foreach($cart as $index => $item){
          // kiểm tra xem chỉ số có tồn tại trong mảng $quantities hay không
     
        // cập nhật số lượng sản phẩm
        $newQuantity = (int)$quanties[$index]; // lấy số lượng sản phẩm từ danh sách số lượng sản phẩm
        $product = Product::find($item->product->id_sanpham); // lấy sản phẩm từ database
        // kiểm tra số lượng sản phẩm có đủ không
        if($newQuantity > $product->so_luong){
                return redirect()->route('cartView')->with('error', "Sản phẩm {$product->ten_sanpham} không đủ số lượng. Chỉ còn {$product->so_luong} sản phẩm có sẵn.");
        }
        
        // nếu số lượng sản phẩm bằng 0 thì xóa sản phẩm đó khỏi giỏ hàng
        if($newQuantity <= 0){
            
            unset($cart[$index]); // xóa sản phẩm khỏi giỏ hàng
            $cart = array_values($cart); // reset index của giỏ hàng
            continue; // reset index
        }
            $cart[$index]->quantity = $newQuantity; // cập nhật số lượng sản phẩm
        }
        // lưu giỏ hàng vào session
        if (empty($cart)) {
            Session::forget($this->strCart);
        } else {
            // lưu giỏ hàng vào session
            Session::put($this->strCart, $cart);
        }
        return redirect()->route('cartView')->with('success','Cập nhật giỏ hảng thành công');
    }
    // thanh toán giỏ hàng
    public function checkOut(){
        return view('pages.checkout');
    }
    public function processOrder(Request $request){

        $cart = Session::get($this->strCart, []); // lấy giỏ hàng từ session
        $numberofOrders = TheOrder::count(); // đếm số lượng đơn hàng
        $order = new TheOrder(); // khởi tạo đơn hàng mới
        $order->tendonhang = 'Đơn hàng số ' . ($numberofOrders + 1); // tạo tên đơn hàng
        $order->tenkhachhang = $request->input('tenkhachhang'); // lấy tên khách hàng từ form
        $order->diachi = $request->input('diachi'); // lấy địa chỉ từ form
        $order->sdt = $request->input('sdt'); // lấy số điện thoại từ form
        $order->email = $request->input('email'); // lấy email từ form
        $order->hinhthucthanhtoan = $request->input('hinhthucthanhtoan'); // lấy hình thức thanh toán từ form
        $order->ngaydat = Carbon::now('Asia/Ho_Chi_Minh'); // lấy ngày đặt hàng format giờ Việt Nam
        $order->trangthai = 0; // trạng thái đơn hàng
        $order->save(); // lưu đơn hàng vào database
        // duyệt qua từng sản phẩm trong giỏ hàng
        foreach($cart as $item){
            $orderDetail = new OrderDetail(); // khởi tạo chi tiết đơn hàng
            $orderDetail->id_donhang = $order->id_donhang; // lấy id đơn hàng
            $orderDetail->id_sanpham = $item->product->id_sanpham; // lấy id sản phẩm
            $orderDetail->thanhtien = $item->product->gia_moi; // lấy đơn giá sản phẩm
            $orderDetail->soluong = $item->quantity; // lấy số lượng sản phẩm
            $orderDetail->save(); // lưu chi tiết đơn hàng vào database
        }
        // xóa giỏ hàng khỏi session
        Session::forget($this->strCart);
        return redirect()->route('home')->with('success', 'Đặt hàng thành công');
    }
}
