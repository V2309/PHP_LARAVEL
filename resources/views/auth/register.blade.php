@extends('layouts.account')

@section('content')

<div class="app">
    <div class="header">
        <div class="grid">
            <div class="header-main">
                <div class="header-logo header-logo1">
                    <a href="{{route('home')}}" class="logo__link">
                        <img src="{{asset('frontend/images/go.png')}}" alt="" width="160px" height="40px">
                    </a>
                    <div class="header-text">Đăng ký GoFood</div>
                </div>
                <a href="" class="help-center">Bạn cần giúp đỡ?</a>
            </div>
        </div>
    </div>
    <div class="app__container" style="background:#eee;padding-top:10px;">

        <div class="form">
            <div class="form-box form-register">
                <h1 style="text-align: center">ĐĂNG KÝ TÀI KHOẢN</h1>
             
                
                <form method="POST" action="{{ route('register') }}">
                 
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="">{{ __('Họ tên:') }}</label>

                        <div class="">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="email" class="">{{ __('Email Address') }}</label>

                        <div class="">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="">{{ __('Password') }}</label>

                        <div class="">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    

                    <div class="row mb-3">
                        <label for="password-confirm" class="">{{ __('Xác nhận mật khẩu') }}</label>

                        <div class="">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                 

                    <button class="fb" type="submit"><span>Đăng ký ngay</span></button>
                    <p style="padding-left: 8px;font-size: 10px;color: #707070;">Bằng cách  <a href="{{route('login')}}" style="color: #0288d1;font-weight: 600;"> đăng nhập</a> hoặc đăng ký, bạn đồng ý với Chính sách quy định của Fooddy </p>

                </form>
            <div style="text-align:center;color:red;" class="error">
                @if(Session::has('error'))
                    {{Session::get('error')}}
                @endif
            </div>

            </div>
          
        </div>

        

    </div>
</div>

{{-- <div class="app">
    <div class="header">
        <div class="grid">
            <div class="header-main">
                <div class="header-logo header-logo1">
                    <a href="{{route('home')}}" class="logo__link">
                        <img src="{{asset('frontend/images/go.png')}}" alt="" width="160px" height="40px">
                    </a>
                    <div class="header-text">Đăng ký GoFood</div>
                </div>
                <a href="" class="help-center">Bạn cần giúp đỡ?</a>
            </div>
        </div>
    </div>
    <div class="app__container" style="background:#eee;padding-top:10px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>
    
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
    
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Họ tên:') }}</label>
    
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
    
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email:') }}</label>
    
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Mật khẩu:') }}</label>
    
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Xác nhận mật khẩu') }}</label>
    
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
    
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div> --}}
@endsection
