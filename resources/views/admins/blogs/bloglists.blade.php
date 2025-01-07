@extends('layouts.admin_layout')
@section('content')

<section class="content-header">
    <style>
        .status-toggle:hover {
            cursor: pointer;
        }
    </style>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Quản Lý Bài Viết</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Quản Lý Bài Viết</li>
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
                   <th>Tiêu đề bài viết</th>
                   <th>Tóm tắt</th>
                   <th>Hình ảnh</th>
                   <th>Ngày tạo</th>
                   <th>Trạng thái</th>
                   <th>Hành động</th>
               </tr>
                @foreach ($blogs as $blog)
           <tr>
               <td>{{ $blog->nameBlog }}</td>
               <td>{{ $blog->shortContent }}</td>
               <td><img src="" alt="{{ $blog->nameBlog }}" style="width: 50px;"></td>
               <td>{{ $blog->created_at }}</td>
               <td>
                   <span style="font-size: 16px" class="badge status-toggle {{ $blog->status === 'Draft' ? 'badge-danger' : 'badge-success' }}" 
                       data-id="{{ $blog->id }}" 
                       id="status-{{ $blog->id }}">
                       {{ $blog->status }}
                   </span>
               </td>
               <td>
                   <button class="btn btn-info view-blog-detail" data-id="{{ $blog->id }}">Detail</button>
               </td>
           </tr>
                @endforeach
            </table>
            {{ $blogs->links('pagination::bootstrap-4') }}
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            Footer
        </div>
        <!-- /.card-footer-->
    </div>
    <!-- /.card -->

    <div class="modal fade" id="blogDetailModal" tabindex="-1" role="dialog" aria-labelledby="blogDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="blogDetailModalLabel">Blog Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Blog details will be loaded here -->
                    <div id="blog-details-content"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Xác nhận trạng thái bài viết --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.status-toggle').on('click', function () {
            const blogId = $(this).data('id');
            const statusElement = $(this);

            $.ajax({
                url: "{{ route('toggleBlogStatus', '') }}/" + blogId,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.success) {
                        const newStatus = response.status;
                        if (newStatus === 'Published') {
                            statusElement.text('Published')
                                         .removeClass('badge-danger')
                                         .addClass('badge-success');
                        } else {
                            statusElement.text('Draft')
                                         .removeClass('badge-success')
                                         .addClass('badge-danger');
                        }
                    } else {
                        alert('Có lỗi xảy ra khi thay đổi trạng thái.');
                    }
                },
                error: function () {
                    alert('Không thể thay đổi trạng thái.');
                }
            });
        });
    });
</script>

{{-- Xem chi tiết bài viết --}}
<script>
    $(document).ready(function() {
        $('.view-blog-detail').click(function() {
            var blogId = $(this).data('id');
            $.ajax({
                url: '/get-blog-details/' + blogId,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        var blog = response.blog;
                        var content = '<h4>' + blog.nameBlog + '</h4>';
                        content += '<p>' + blog.mainContent + '</p>';
                        $('#blog-details-content').html(content);
                        $('#blogDetailModal').modal('show');
                    } else {
                        alert(response.error);
                    }
                }
            });
        });
    });
</script>
@endsection
