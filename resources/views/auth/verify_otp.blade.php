@extends('layouts.account')
@section('content')
<div class="container">
    <form method="POST" action="{{ route('phone.verify-otp') }}"> <!-- Sửa action -->
        @csrf
        <input type="hidden" name="phone_number" value="{{ $phone_number }}">
        <div class="form-group">
            <label>Nhập mã OTP (được gửi tới email của bạn)</label>
            <input type="text" name="otp" class="form-control" required>
            @error('otp')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Xác minh</button>
    </form>
</div>
@endsection