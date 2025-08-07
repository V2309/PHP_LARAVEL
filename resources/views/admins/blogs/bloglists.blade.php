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
            <a style="margin-bottom:20px;" class="btn btn-primary" href="{{ route('createblog')}}">
                Create
            </a>
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
            <td>{{ Str::limit($blog->nameBlog, 20, '...') }}</td>
            <td>{{ Str::limit($blog->shortContent, 50, '...') }}</td>  
               <td><img src="{{asset('frontend/images/'.$blog->image)}}" alt="{{ $blog->nameBlog }}" style="width: 50px;"></td>
               <td>{{ $blog->created_at }}</td>
               <td>
                   <span style="font-size: 16px" class="badge status-toggle {{ $blog->status === 'Draft' ? 'badge-danger' : 'badge-success' }}" 
                       data-id="{{ $blog->id }}" 
                       id="status-{{ $blog->id }}">
                       {{ $blog->status }}
                   </span>
               </td>
               <td>
                   <button class=" btn btn-default view-blog-detail" data-id="{{ $blog->id }}">
                          <i class="fas fa-eye "></i>
                   </button>
                    <a href="{{ route('blog.edit.form', $blog->id) }}" class="btn btn-primary">
    <i class="fas fa-edit text-white"></i>
</a>
                        <a href="javascript:void(0)" class="btn btn-danger action-icon delete-blog" data-id="{{ $blog->id }}" title="Xóa" 
                            onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?') && deleteBlog({{ $blog->id }})">
                             <i class="fas fa-trash-alt text-white"></i>
                         </a>
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
                url: "{{ route('toggleBlogStatus', '') }}" + blogId, // Sửa URL
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.success) {
                        const newStatus = response.status; // Lấy từ JSON
                        if (newStatus === 'Hiện') {
                            statusElement.text('Hiện')
                                        .removeClass('badge-danger')
                                        .addClass('badge-success');
                        } else {
                            statusElement.text('Ẩn')
                                        .removeClass('badge-success')
                                        .addClass('badge-danger');
                        }
                    } else {
                        alert('Có lỗi xảy ra khi thay đổi trạng thái.');
                    }
                },
                error: function (xhr) {
                    alert('Không thể thay đổi trạng thái: ' + xhr.status);
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
                        content += '<img src="{{asset('frontend/images/'.$blog->image)}}" alt="{{ $blog->nameBlog }}" style="max-width: 100%;height: auto;">';
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
 // Hàm xóa blog
 function deleteBlog(blogId) {
        $.ajax({
            url: '{{ route("deleteBlog", "") }}/' + blogId,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                _method: 'DELETE'
            },
            success: function(response) {
                if (response.success) {
                    alert('Xóa bài viết thành công!');
                    location.reload(); // Tải lại trang để cập nhật danh sách
                } else {
                    alert(response.error || 'Có lỗi khi xóa bài viết.');
                }
            },
            error: function(xhr) {
                alert('Không thể xóa bài viết: ' + xhr.statusText);
            }
        });
    }
</script>
@endsection
