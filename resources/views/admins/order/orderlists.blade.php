@extends('layouts.admin_layout')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Quản Lý Đơn Hàng</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Blank Page</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">

           <table class="table">
               <tr class="table-info">
                   <th>
                       Tên đơn hàng
                   </th>
                   <th>
                      Tên khách hàng

                   </th>
                  
                   
                   <th>
                       Số điện thoại
                   </th>
                     <th>
                          Ngày đặt
                        </th>
                  
                   <th>
                       Trạng thái
                   </th>
                   <th>
                    Hành động
                   </th>
                   
               </tr>
                @foreach ($orders as $order)
                
           <tr>
              
               <td>{{$order ->tendonhang}}</td>

               <td>{{$order->tenkhachhang}}</td>

              

               <td>{{$order->sdt}}</td>

               
               <td>{{$order->ngaydat}}</td>
             
               <td>
                @if ($order->trangthai == 0)
                <a href="#" class="btn btn-primary">Chờ xác nhận</a>
              
                @else
               <a href="#" class="btn btn-success">Đã xác nhận</a>
                @endif
               </td>
               <td>
                <a class="btn btn-dark edit-product" href="{{route('confirmOrder',['id_donhang'=>$order->id_donhang])}}" >Xác nhận</a>
              
                <button class="btn btn-info view-order-detail" data-id="{{ $order->id_donhang }}">Detail</button>
                </td>
           </tr>
                @endforeach

            </table>
            {{ $orders->links('pagination::bootstrap-4') }}
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            Footer
        </div>
        <!-- /.card-footer-->
    </div>
    <!-- /.card -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailModalLabel">Order Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Order details will be loaded here -->
                    <div id="order-details-content"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.view-order-detail').click(function() {
            var orderId = $(this).data('id');
            $.ajax({
                url: '/get-order-details/' + orderId,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        var order = response.order;
                        var content = '<ul>';
                        order.forEach(function(detail) {
                            content += '<li>Sản phẩm: ' + detail.product.ten_sanpham + ', Số lượng: ' + detail.soluong + ', Thành tiền: ' + detail.thanhtien + '</li>';
                        });
                        content += '</ul>';
                        $('#order-details-content').html(content);
                        $('#orderDetailModal').modal('show');
                    } else {
                        alert(response.error);
                    }
                }
            });
        });
    });
</script>
@endsection