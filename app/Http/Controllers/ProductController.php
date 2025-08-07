<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Group;
class ProductController extends Controller
{
    // danh sach san pham kèm theo nhóm sản phẩm
    public function productcategory($id_nhomsp)
    {
        // Lấy sản phẩm dựa trên id_nhomsp
        $products = Product::where('id_nhomsp', $id_nhomsp)->get();
    
        // Lấy thông tin nhóm sản phẩm để hiển thị danh mục bên trái
        $categories = Category::all();
        $groups = Group::with('category')->get();
        $namepro = Group::where('id_nhomsp', $id_nhomsp)->first();;
        session(['product_group' => $namepro]);
    
        return view('pages.productcategory', compact('categories', 'products','groups'));
    }
    // chi tiết sản phẩm
    public function productDetail($id_sanpham){
        $product = Product::with(['category', 'group'])->findOrFail($id_sanpham);

        // Trả về view chi tiết sản phẩm
        return view('pages.productDetails', compact('product'));
    }
   
    
}
