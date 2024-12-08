<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    
      public function index()
      {
  
          $products=Product::paginate(10);
          return view('pages.home',compact('products'));
      }
      // tim kiem san pham 
      public function search(Request $request)
      {
          $searching = $request->input('searching');
          $products = Product::with(['category', 'group'])
              ->where('ten_sanpham', 'LIKE', "%{$searching}%")
              ->paginate(3);
      
          $categories = Category::all();
          $groups = Group::all();
      
          return view('pages.home', compact('products', 'categories', 'groups'));
      }
         
      public function about()
      {
          return view('pages.about');
      }
      public function support()
      {
          return view('pages.support');
      }
   
}
