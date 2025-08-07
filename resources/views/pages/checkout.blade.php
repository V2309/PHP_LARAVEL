@extends('layouts.layout')
@section('content')
<div class="app__container">
    <div class="cart-order">
        <div class="info-customer">
            <a href="{{ route('cartView') }}" class="title-head" style="color:#000;text-decoration:none;width:100%;display:block">
                <i class="fas fa-backward"></i>
                Xem lại giỏ hàng
            </a>
            <div class="title-head">
                Thông tin Khách hàng
            </div>
            <form id="checkoutForm" action="{{ route('order.process') }}" method="POST">
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
                    <div class="pay-ment" style="background: #eee">
                        <div class="form-check" style="display:flex;align-items: center;">
                            <input type="radio" class="form-check-input" id="radio1" name="hinhthucthanhtoan" value="Thanh toán khi nhận hàng" checked>
                            <label class="form-check-label" for="radio1">Thanh toán khi nhận hàng</label>
                        </div>
                        <div class="form-check" style="display:flex;align-items: center;">
                            <input type="radio" class="form-check-input" id="radio2" name="hinhthucthanhtoan" value="Chuyển khoản">
                            <label class="form-check-label" for="radio2">Chuyển khoản</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="radio3" name="hinhthucthanhtoan" value="ZaloPay">
                            <label class="form-check-label" for="radio3">ZaloPay</label>
                        </div>
                    </div>
                    <button class="btns btn--primary btn-ord" type="submit">Hoàn tất mua hàng</button>
                </div>
            </form>
            <script>
                document.getElementById('checkoutForm').addEventListener('submit', function(event) {
                    var paymentMethod = document.querySelector('input[name="hinhthucthanhtoan"]:checked').value;
                    if (paymentMethod === 'ZaloPay') {
                        event.preventDefault();
                        payWithZaloPay();
                    }
                });

                function payWithZaloPay() {
                    fetch('{{ route("payWithZaloPay") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            tenkhachhang: document.querySelector('input[name="tenkhachhang"]').value,
                            diachi: document.querySelector('input[name="diachi"]').value,
                            sdt: document.querySelector('input[name="sdt"]').value,
                            email: document.querySelector('input[name="email"]').value,
                            hinhthucthanhtoan: 'ZaloPay'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.return_code === 1) {
                            window.location.href = data.order_url;
                        } else {
                            alert('Thanh toán ZaloPay thất bại: ' + data.return_message);
                            console.log(data);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            </script>
        </div>
        <div class="info-order">
            @if ($cart->items->isNotEmpty())
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
                                $subtotal = $cart->items->sum(function ($item) {
                                    return $item->quantity * $item->product->gia_moi;
                                });
                            @endphp
                            <div class="tamtinh">
                                Tiền hàng:
                            </div>
                            <div class="money_tamtinh">{{ number_format($subtotal, 0, ',', '.') }}₫</div>
                        </div>
                        <div class="phi_gh">
                            @php
                                $count = $cart->items->sum('quantity');
                            @endphp
                            <div class="tamtinh">
                                Số lượng:
                            </div>
                            <div class="money_tamtinh">{{ $count }}</div>
                        </div>
                        <div class="phi_gh">
                            @php
                                $phuthu = 0;
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
                    <img style="width:100%" src="{{ asset('frontend/images/empty-cart.jpg') }}" />
                </div>
            @endif
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#checkoutForm").validate({
        rules: {
            tenkhachhang: { required: true },
            sdt: { required: true },
            diachi: { required: true },
            email: { required: true },
            hinhthucthanhtoan: { required: true }
        }
    });
</script>
@endsection