@extends('layouts.account')
@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-6">
        <div class="card shadow-lg p-4">
            <h3 class="text-center mb-4">Nhập thông tin</h3>
            <form method="POST" action="{{ route('phone.send') }}">
                @csrf
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}" required>
                    @error('phone_number')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary" style="font-size:16px">Gửi OTP</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
