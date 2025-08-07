@extends('layouts.admin_layout')
@section('content')
<div class="container">
    <h2>Thêm Blog Mới</h2>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('blog.add') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="nameBlog">Tiêu đề Blog</label>
            <input type="text" name="nameBlog" class="form-control" required>
            @error('nameBlog')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="shortContent">Nội dung ngắn</label>
            <textarea name="shortContent" class="form-control" rows="3" required></textarea>
            @error('shortContent')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="mainContent">Nội dung chính</label>
            <textarea name="mainContent" class="form-control" rows="5" required></textarea>
            @error('mainContent')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Thêm Blog</button>
    </form>
</div>
@endsection