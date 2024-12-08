@extends('layouts.layout')
@section('content')
<div class="app__container">
    <div class=" cart-order">
      <div class="info-customer">
        <a href="" class="title-head" style="color:#000;text-decoration:none;width:100%;display:block">
            <i class="fas fa-backward"></i>
            Xem lại giỏ hàng
        </a>
        <div class="title-head">
            Thông tin Khách hàng
        </div>
      
        <form action="{{route('processOrder')}}" method="POST">
            @csrf
            <div class="form-order">
                <div class="mb-3">
                    <label>Họ tên khách hàng:</label>
                    <input type="text" name="tenkhachhang" class="form-control" required />
                </div>
                <div class="mb-3 input-form">
                    <label>Địa chỉ nhận hàng:</label>
                    <input type="text" name="diachi" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label>Số điện thoại:</label>
                    <input type="text" name="sdt" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label>Email khách hàng:</label>
                    <input type="email" name="email" class="form-control" required />
                </div>
                
                <label>Hình thức thanh toán:</label>
                <div class="form-check">
                    <input type="radio" class="form-check-input" id="radio1" name="hinhthucthanhtoan" value="Thanh toán khi nhận hàng" checked>
                    <label class="form-check-label" for="hinhthucthanhtoan">Thanh toán khi nhận hàng</label>
                  </div>
                  <div class="form-check">
                    <input type="radio" class="form-check-input" id="radio2" name="hinhthucthanhtoan" value="Chuyển khoản">
                    <label class="form-check-label" for="hinhthucthanhtoan">Chuyển khoản</label>
                  </div>
                <button class="btns btn--primary btn-ord" type="submit">Hoàn tất mua hàng</button>
            </div>
        </form>
      </div>
       
       <div class="info-order">
        @if (Session::has('Carts'))
        <div class="title-head">
            Thông tin hóa đơn
        </div>
        <div class="block">
            <div class="block-product">
                <div class="buy-ticket">
                    <div class="ticket">
                        <a href="" class="vouchers">
                            <i class="fas fa-ticket"></i>
                            Phiếu mua hàng
                        </a>
                        <i class="fa-solid fa-angle-right"></i>
                    </div>
                </div>

                <div class="phi_gh">
                    @php
                        $carts = Session::get('Carts');
                        $subtotal = array_sum(array_map(function($cart) {
                        return $cart->quantity * $cart->product->gia_moi;
                    }, $carts));
                    @endphp
                    <div class="tamtinh">
                        Tiền hàng:
                    </div>
                    <div class="money_tamtinh">{{ number_format($subtotal, 0, ',', '.') }}₫</div>
                </div>

                <div class="phi_gh">
                    @php
                         $count = array_sum(array_map(function($cart) {
        return $cart->quantity;
    }, $carts));
                    @endphp
                    <div class="tamtinh">
                        Số lượng:
                    </div>
                    <div class="money_tamtinh">{{ $count }}</div>
                </div>
                <div class="phi_gh">
                    @php
                        $phuthu = 15000;
                    @endphp
                    <div class="tamtinh">
                        Tiền giao hàng, phụ phí:
                    </div>
                    <div class="money_tamtinh">{{ number_format($phuthu, 0, ',', '.') }}₫</div>
                </div>
                <div class="phi_gh">
                    @php
                        $total = $subtotal + $phuthu;
                    @endphp
                    <div class="tamtinh">
                        Tổng đơn hàng:
                    </div>
                    <div class="money_tamtinh">{{ number_format($total, 0, ',', '.') }}₫</div>
                </div>
            </div>
        </div>
    @else
        <div class="no-cart">
            <img style="width:100%" src="{{ asset('images/Products/empty-cart.jpg') }}" />
        </div>
    @endif
       </div>
    </div>
</div>
<script type="text/javascript">
    $("#customer-form").validate({
        rules: {
            tenkhachhang: {
                required: true
            },
            sdt: {
                required: true
            },
            diachi: {
                required: true
            },
            email: {
                required: true
            }
            ,
            hinhthucthanhtoan: {
                required: true
            }
        }
    });
</script>
@endsection