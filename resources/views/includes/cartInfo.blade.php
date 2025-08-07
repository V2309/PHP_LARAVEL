<div class="header__cart">
    <div class="cart-wrap">
        @if ($cart->items->isNotEmpty())
            @php
                $count = $cart->items->sum('quantity');
            @endphp
            <i class="cart-icon fas fa-cart-shopping"></i>
            <span class="cart-name">Giỏ hàng</span>
            <span class="cart-notice">{{ $count }}</span>
            <div class="header__cart-list">
                <h4 class="header__cart-heading">
                    Sản phẩm đã thêm
                </h4>
                <ul class="cart-list-item">
                    @foreach ($cart->items as $item)
                        <li class="cart-item">
                            <img src="{{ asset('frontend/images/' . $item->product->hinh_sanpham) }}" alt="" class="cart-img">
                            <div class="cart-item-info">
                                <div class="cart-item-head">
                                    <h5 class="cart-item-name">{{ $item->product->ten_sanpham }}</h5>
                                    <div class="cart-item-wrap">
                                        <span class="cart-item-price">{{ number_format($item->product->gia_moi, 0, ',', '.') }}₫</span>
                                        <span class="cart-item-multiply">x</span>
                                        <span class="cart-item-qnt">{{ $item->quantity }}</span>
                                    </div>
                                </div>
                                <div class="cart-item-body">
                                    <span class="cart-item-desc">
                                        Phân loại: {{ $item->product->group->tennhom }}
                                    </span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('cartView') }}" class="cart-views btns btn--primary">Xem giỏ hàng</a>
            </div>
        @else
            <i class="cart-icon fas fa-cart-shopping"></i>
            <span class="cart-name">Giỏ hàng</span>
            <span class="cart-notice">0</span>
            <div class="header__cart-list">
                <img src="{{ asset('frontend/images/no-cart-icon.png') }}" alt="" class="cart-no-cart-img">
                <a href="{{ route('cartView') }}" class="cart-views btns btn--primary">Xem giỏ hàng</a>
            </div>
        @endif
    </div>
</div>