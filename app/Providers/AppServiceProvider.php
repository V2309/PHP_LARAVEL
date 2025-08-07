<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Group;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Chia sẻ categories và groups cho tất cả view
        $categories = Category::all();
        $groups = Group::with('category')->get();
        View::share([
            'categories' => $categories,
            'groups' => $groups,
        ]);

        // Sử dụng View Composer để truyền $cart vào tất cả view
        View::composer('*', function ($view) {
            $sessionId = Session::getId();
            $user = Auth::user();

            if ($user) {
                $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            } else {
                $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
            }

            $cart->load('items.product'); // Load quan hệ items và product
            $view->with('cart', $cart);
        });
    }
}