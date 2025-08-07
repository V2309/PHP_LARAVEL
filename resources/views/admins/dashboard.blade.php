@extends('layouts.admin_layout')

@section('content')
<div class="content">
    <div class="container-fluid">
        <!-- Thống kê -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $orderCount }}</h3>
                        <p>Số đơn hàng</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $productCount }}</h3>
                        <p>Số sản phẩm</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cube"></i>
                    </div>
                    <a href="#" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $blogCount }}</h3>
                        <p>Số bài viết</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-document-text"></i>
                    </div>
                    <a href="#" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $groupCount }}</h3>
                        <p>Số nhóm sản phẩm</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-folder"></i>
                    </div>
                    <a href="#" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $categoryCount }}</h3>
                        <p>Số loại sản phẩm</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pricetags"></i>
                    </div>
                    <a href="#" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Biểu đồ -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Thống kê đơn hàng (7 ngày qua)</h3>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="#" class="btn btn-tool btn-sm">
                                <i class="fas fa-bars"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="position-relative mb-4">
                            <canvas id="orders-chart" height="200"></canvas>
                        </div>
                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2">
                                <i class="fas fa-square text-primary"></i> Tuần này
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card" style="height:314px; overflow-y:scroll">
                    <div class="card-header border-0">
                        <h3 class="card-title">Sản phẩm bán chạy</h3>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="#" class="btn btn-tool btn-sm">
                                <i class="fas fa-bars"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng bán</th>
                                    <th>Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <img src="{{ asset('frontend/images/' . $product->hinh_sanpham) }}" class="img-circle img-size-32 mr-2" alt="{{ $product->ten_sanpham }}">
                                        {{ $product->ten_sanpham }}
                                    </td>
                                    <td>{{ number_format($product->gia_moi, 0, ',', '.') }} ₫</td>
                                    <td>{{ $product->total_sold ?? 0 }}</td>
                                    <td>
                                        <a href="#" class="text-muted">
                                            <i class="fas fa-search"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('orders-chart').getContext('2d');
        var ordersChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels), // ['01/03', '02/03', ...]
                datasets: [{
                    label: 'Số đơn hàng',
                    data: @json($data), // [5, 10, 3, ...]
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection