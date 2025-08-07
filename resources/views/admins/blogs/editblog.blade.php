@extends('layouts.admin_layout')
@section('content')
<div class="container">
    <h2>Chỉnh sửa Blog</h2>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="nameBlog">Tiêu đề Blog</label>
            <input type="text" name="nameBlog" class="form-control" value="{{ $blog->nameBlog }}" required>
            @error('nameBlog')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="shortContent">Nội dung ngắn</label>
            <textarea name="shortContent" class="form-control" rows="3" required>{{ $blog->shortContent }}</textarea>
            @error('shortContent')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="image">Hình ảnh (Để trống nếu không đổi)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @if ($blog->image)
                <img src="{{ asset('frontend/images/' . $blog->image) }}" alt="{{ $blog->nameBlog }}" style="max-width: 200px; margin-top: 10px;">
            @endif
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="mainContent">Nội dung chính</label>
            <textarea name="mainContent" class="form-control" rows="5" required>{{ $blog->mainContent }}</textarea>
            @error('mainContent')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật Blog</button>
        <a href="{{ route('bloglists') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection