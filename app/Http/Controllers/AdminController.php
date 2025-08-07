<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

use App\Models\TheOrder;
use App\Models\OrderDetail;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class AdminController extends Controller
{
     public function __construct()
    {

    $this->middleware('auth');
  

    }
    // trang chu admin
    public function dashboard()
    {
        $orderCount = TheOrder::count();
        $productCount = Product::count();
        $blogCount = Blog::count();
        $groupCount = Group::count();
        $categoryCount = Category::count();
        // Lấy dữ liệu đơn hàng trong 7 ngày qua
        $orderData = TheOrder::selectRaw('DATE(ngaydat) as date, COUNT(*) as count')
            ->where('ngaydat', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $orderData->pluck('date')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d/m');
        })->toArray();
        $data = $orderData->pluck('count')->toArray();

        // Lấy 10 sản phẩm bán chạy nhất
        $products = Product::leftJoin('orderdetail', 'product.id_sanpham', '=', 'orderdetail.id_sanpham')
            ->select('product.*', DB::raw('SUM(orderdetail.soluong) as total_sold')) // Sử dụng DB::raw()
            ->groupBy('product.id_sanpham', 'product.ten_sanpham', 'product.gia_moi', 'product.hinh_sanpham', 'product.id_loai_sanpham', 'product.gia_cu', 'product.thongtin_km', 'product.so_luong', 'product.id_nhomsp', 'product.created_at', 'product.updated_at')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        return view('admins.dashboard', compact(
            'orderCount', 
            'productCount', 
            'blogCount', 
            'groupCount', 
            'categoryCount', 
            'labels', 
            'data', 
            'products'
        ));
    }

    // xu ly cac chuc nang cua product
     public function productlists()
     {   
       // $products=Product::all();
        $products=Product::with(['category','group' ])->paginate(3);
        $categories=Category::all();
        $groups=Group::all();
         return view('admins.products.productlists',compact('products','categories','groups'));
     }
     // chi tiet san pham
        public function productDetail($id_sanpham)
    {  
        
        $product = Product::with(['category', 'group'])->findOrFail($id_sanpham);

        // Trả về view chi tiết sản phẩm
        return view('admins.products.productdetail', compact('product'));
    }

    // hien thi view create product
   
        public function createproduct()
    {
        $categories=Category::all();
        $groups=Group::all();
        return view('admins.products.createproduct',compact('categories','groups'));
    }
    // tim kiem san pham
    public function searchProduct(Request $request)
{
    $searching = $request->input('searching');
    $products = Product::with(['category', 'group'])
        ->where('ten_sanpham', 'LIKE', "%{$searching}%")
        ->paginate(3);

    $categories = Category::all();
    $groups = Group::all();

    return view('admins.products.productlists', compact('products', 'categories', 'groups'));
}
   // thêm mới sản phẩm
    public function addproduct(Request $request){
        $request -> validate([
            'ten_sanpham'=>'required',
            'gia_moi'=>'required',
            'gia_cu'=>'required',
            'id_loai_sanpham'=>'required',
            'hinh_sanpham'=>'required|image',
            'thongtin_km'=>'nullable',
            'so_luong'=>'required',
            'id_nhomsp'=>'required',
            
        ]);     
        $filename = null;
        if ($request->hasFile('hinh_sanpham')) {
            $file = $request->file('hinh_sanpham');
            $filename = $file->getClientOriginalName();
            $file->move(public_path('backend/images'), $filename);
        
            // Copy the file to the frontend images directory
            copy(public_path('backend/images/' . $filename), public_path('frontend/images/' . $filename));
        
        }
        Product::create([
            'ten_sanpham'=>$request->ten_sanpham,
            'gia_moi'=>$request->gia_moi,
            'gia_cu'=>$request->gia_cu,
            'id_loai_sanpham'=>$request->id_loai_sanpham,
            'hinh_sanpham'=>$filename,
            'thongtin_km'=>$request->thongtin_km,
            'so_luong'=>$request->so_luong,
            'id_nhomsp'=>$request->id_nhomsp,
        ]);

        return redirect()->route('productlists')->with('success', 'Product created successfully');
    }
    
    // lay thong tin product
    public function getProduct($id_sanpham)
    {
        $product = Product::find($id_sanpham);
        $categories = Category::all();
        $groups = Group::all();
    
        if ($product) {
            return response()->json([
                'success' => true,
                'product' => $product,
                'categories' => $categories,
                'groups' => $groups
            ]);
        } else {
            return response()->json(['success' => false, 'error' => 'Product not found'], 404);
        }
    }
     // cap nhat product
     public function updateProduct(Request $request , $id_sanpham){

        $product = Product::find($id_sanpham);
        if ($product) {
            $product->ten_sanpham = $request->ten_sanpham;
            $product->gia_moi = $request->gia_moi;
            $product->gia_cu = $request->gia_cu;
            $product->id_loai_sanpham = $request->id_loai_sanpham;
            if ($request->hasFile('hinh_sanpham')) {
                // Xóa ảnh cũ nếu có
                if ($product->hinh_sanpham && file_exists(public_path('backend/images/' . $product->hinh_sanpham))) {
                    unlink(public_path('backend/images/' . $product->hinh_sanpham));
                }
                if ($product->hinh_sanpham && file_exists(public_path('frontend/images/' . $product->hinh_sanpham))) {
                    unlink(public_path('frontend/images/' . $product->hinh_sanpham));
                }
    
                // Lưu ảnh mới
                $file = $request->file('hinh_sanpham');
                $filename = $file->getClientOriginalName();
                $file->move(public_path('backend/images'), $filename);
                copy(public_path('backend/images/' . $filename), public_path('frontend/images/' . $filename));
                $product->hinh_sanpham = $filename;
            }
            $product->thongtin_km = $request->thongtin_km;
            $product->so_luong = $request->so_luong;
            $product->id_nhomsp = $request->id_nhomsp;
            $product->save();
            
            return response()->json(['success' => 'Category updated successfully']);
       } else {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    // xoa product
    public function deleteProduct($id_sanpham)
    {
            $product = Product::find($id_sanpham);
        
            if ($product) {
                $product->delete();
                return response()->json(['success' => 'Product deleted successfully']);
            } else {
                return response()->json(['error' => 'Product not found'], 404);
            }
    }
   
    // xu ly cac chuc nang cua category
    // hien thi danh muc san pham
    public function categorylists()
    {
        $categories=Category::paginate(3);
    
        return view('admins.categories.categorylists',compact('categories'));
    }
    // hien thi view create category

    public function createCategory()
    {
        return view('admins.categories.createcategory');
    }
    // them moi category
        public function addCategory(Request $request){
        $request->validate([
            'id_loaisp' => 'required',
            'tenloaisp' => 'required',
            'anh_loaisp' => 'required|image'
        ]);
        $filename = null;
        if ($request->hasFile('anh_loaisp')) {
            $file = $request->file('anh_loaisp');
            $filename = $file->getClientOriginalName();
            $file->move(public_path('backend/images'), $filename);
        
            // Copy the file to the frontend images directory
            copy(public_path('backend/images/' . $filename), public_path('frontend/images/' . $filename));
        
        }
        Category::create([
            'id_loaisp' => $request->id_loaisp,
            'tenloaisp' => $request->tenloaisp,
            'anh_loaisp' => $filename,
    
        ]);

        return redirect()->route('categorylists')->with('success', 'Category created successfully');
    }
    // xoa category
    public function deleteCategory($id_loaisp)
    {
        $category = Category::where('id_loaisp', $id_loaisp)->first();
        if ($category) {
            $category->delete();
           return response()->json(['success' => 'Category deleted successfully']);
         
        } else {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    // lay thong tin category
    public function getCategory($id_loaisp)
    {
        $category = Category::find($id_loaisp);
        
        if ($category) {
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        } else {
            return response()->json(['success' => false, 'error' => 'Category not found'], 404);
        }
}

 public function updateCategory(Request $request, $id_loaisp)
 {
     $category = Category::find($id_loaisp);
     if ($category) {
         $category->tenloaisp = $request->tenloaisp;
        // $category->trangthai = $request->trangthai;
         if ($request->hasFile('anh_loaisp')) {
            // Xóa ảnh cũ nếu có
         //   if ($category->anh_loaisp && file_exists(public_path('backend/images/' . $category->anh_loaisp))) {
         //       unlink(public_path('backend/images/' . $category->anh_loaisp));
         //   }
         //   if ($category->anh_loaisp && file_exists(public_path('frontend/images/' . $category->anh_loaisp))) {
        //        unlink(public_path('frontend/images/' . $category->anh_loaisp));
        //    }

            // Lưu ảnh mới
            $file = $request->file('anh_loaisp');
            $filename =$file->getClientOriginalName();
            $file->move(public_path('backend/images'), $filename);
            copy(public_path('backend/images/' . $filename), public_path('frontend/images/' . $filename));
            $category->anh_loaisp = $filename;
        }

          $category->save();
         return response()->json(['success' => 'Category updated successfully']);
    } else {
        return redirect()->route('categorylists')->with('error', 'Category not found');
     }
 }
 // toggle status
 public function toggleStatus(Request $request, $id_loaisp)
 {
     $category = Category::findOrFail($id_loaisp);
 
     // Thay đổi trạng thái
     $category->trangthai = $category->trangthai === 'Hiện' ? 'Ẩn' : 'Hiện';
     $category->save();
 
     return response()->json([
         'success' => true,
         'trangthai' => $category->trangthai,
     ]);
 }

    // xu ly cac chuc nang cua group
    public function grouplists()
    {
        $groups=Group::paginate(3);
        return view('admins.groups.grouplists',compact('groups'));
    }
    public function creategroup()
    {
        $categories = Category::all();
        return view('admins.groups.creategroup',compact('categories'));
    }
    public function addGroup(Request $request){
        $request->validate([
            'id_nhomsp' => 'required',
            'tennhom' => 'required',
            'id_loaisp' => 'required',
            
        ]);

        Group::create([
          //  $request->all(),

            'id_nhomsp' => $request->id_nhomsp,
            'tennhom' => $request->tennhom,
            'id_loaisp' => $request->id_loaisp,
           
        ]);

        return redirect()->route('grouplists')->with('success', 'Group created successfully');
    }
    // delete group
    public function deleteGroup($id_nhomsp){
        $group = Group::where('id_nhomsp', $id_nhomsp)->first();
        if ($group) {
            $group->delete();
          return redirect()->route('grouplists')->with('success', 'Group deleted successfully');
        } else {
            return response()->json(['error' => 'Group not found'], 404);
        }

    }
    // get group 
    public function getGroup($id_nhomsp)
    {
        $group = Group::find($id_nhomsp);
        $categories = Category::all();
        if ($group) {
            return response()->json([
                'success' => true,
                'group' => $group,
                'categories' => $categories
            ]);
        } else {
            return response()->json(['success' => false, 'error' => 'Group not found'], 404);
        }
    }
    // update group
    public function updateGroup(Request $request, $id_nhomsp)
    {
        $group = Group::find($id_nhomsp);
        if ($group) {
            $group->tennhom = $request->tennhom;
            $group->id_loaisp = $request->id_loaisp;
            $group->save();
            return response()->json(['success' => 'Group updated successfully']);
        } else {
            return response()->json(['error' => 'Group not found'], 404);
        }
    }
    // hien thi don hang
    public function orderlists()

    {   $orders = TheOrder::with('orderDetails')->paginate(3);
        return view('admins.order.orderlists',compact('orders'));   
    }
    // xac nhan don hang

    public function confirmOrder($id_donhang)
    {
        $order = TheOrder::findOrFail($id_donhang);
 
     // Thay đổi trạng thái
     $order->trangthai = $order->trangthai === 'Đã xác nhận' ? 'Chờ xác nhận' : 'Đã xác nhận';
     $order->save();
 
     return response()->json([
         'success' => true,
         'trangthai' => $order->trangthai,
     ]);
    }
    // chi tiet don hang
    public function getOrderDetails($id_donhang)
    {
        $order = OrderDetail::with('product')->where('id_donhang', $id_donhang)->get();
        if ($order) {
            return response()->json(['success' => true, 'order' => $order]);
        } else {
            return response()->json(['success' => false, 'error' => 'Order details not found']);
        }
        
    }
    // xoa don hang
    public function deleteOrder($id_donhang)
    {
        $order = TheOrder::find($id_donhang);
        if ($order) {
            // Xóa các chi tiết đơn hàng liên quan trước
            OrderDetail::where('id_donhang', $id_donhang)->delete();
            // Xóa đơn hàng
            $order->delete();
            return response()->json(['success' => 'Đơn hàng đã được xóa thành công']);
        } else {
            return response()->json(['error' => 'Không tìm thấy đơn hàng'], 404);
        }
    }
    // blog 
    public function bloglists()
    {
        $blogs=Blog::paginate(3);
        return view('admins.blogs.bloglists',compact('blogs'));
    }
    public function toggleBlogStatus($id_blog){
        $blog = Blog::findOrFail($id_blog);
        $blog->status = $blog->status === 'Hiện' ? 'Ẩn' : 'Hiện';
        $blog->save();
        return response()->json([
            'success' => true,
            'status' => $blog->status,
        ]);
    }
    public function getBlogDetails($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            return response()->json([
                'success' => true,
                'blog' => $blog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Không tìm thấy bài viết hoặc có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
    // hien thi form them moi blog
    public function createBlog()
    {
        return view('admins.blogs.createblog');
    }
    // them moi blog
    public function addBlog(Request $request)
    {
        $request->validate([
            'nameBlog' => 'required',
            'shortContent' => 'required',
            'image' => 'required|image', // Giới hạn file ảnh
            'mainContent' => 'required',
           
        ]);

        $filename = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName(); // Thêm timestamp để tránh trùng tên
            $file->move(public_path('backend/images'), $filename);
            // Copy sang thư mục frontend
            copy(public_path('backend/images/' . $filename), public_path('frontend/images/' . $filename));
        }

        Blog::create([
            'nameBlog' => $request->nameBlog,
            'shortContent' => $request->shortContent,
            'image' => $filename,
            'mainContent' => $request->mainContent,
        ]);

        return redirect()->route('bloglists')->with('success', 'Blog created successfully');
    }
    // xóa blog hiện thị alert xác nhân rồi mới xóa
    public function deleteBlog($id)
    {
        $blog = Blog::find($id);
        if ($blog) {
            $blog->delete();
            return response()->json(['success' => 'Blog deleted successfully']);
        } else {
            return response()->json(['error' => 'Blog not found'], 404);
        }
    }
    // Hàm hiển thị form chỉnh sửa (nếu cần)
    public function showEditForm($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admins.blogs.editblog', compact('blog'));
    }

    // Hàm cập nhật blog
    public function updateBlog(Request $request, $id)
    {
        $request->validate([
            'nameBlog' => 'required',
            'shortContent' => 'required',
            'image' => 'nullable|image', // Ảnh không bắt buộc
            'mainContent' => 'required',
        ]);

        $blog = Blog::findOrFail($id);

        $filename = $blog->image; // Giữ ảnh cũ nếu không upload ảnh mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($filename && file_exists(public_path('backend/images/' . $filename))) {
                unlink(public_path('backend/images/' . $filename));
                unlink(public_path('frontend/images/' . $filename));
            }
            // Upload ảnh mới
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('backend/images'), $filename);
            copy(public_path('backend/images/' . $filename), public_path('frontend/images/' . $filename));
        }

        $blog->update([
            'nameBlog' => $request->nameBlog,
            'shortContent' => $request->shortContent,
            'image' => $filename,
            'mainContent' => $request->mainContent,
            'status' => $blog->status, // Giữ nguyên status (mặc định 'active' khi tạo)
        ]);

        return redirect()->route('bloglists')->with('success', 'Blog updated successfully');
    }

   
}
