@extends('adminlte::auth.login')

@section('auth_header', __('Login'))

@section('auth_body')
    @if (session('status'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error')) {{-- Tambahkan ini untuk menampilkan error dari social login --}}
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('login') }}" method="post">
        @csrf

        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email') }}" value="{{ old('email') }}" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" required autocomplete="current-password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Log In') }}</button>
            </div>
        </div>
    </form>

    {{-- --- TOMBOL LOGIN DENGAN GOOGLE --- --}}
    <div class="social-auth-links text-center mt-2 mb-3">
        <p>- OR -</p>
        <a href="{{ route('auth.google') }}" class="btn btn-block btn-danger">
            <i class="fab fa-google-plus mr-2"></i> {{ __('Login using Google+') }}
        </a>
        {{-- Anda bisa menambahkan provider lain di sini (misal Facebook, GitHub) --}}
    </div>
    {{-- --- AKHIR TOMBOL LOGIN DENGAN GOOGLE --- --}}

@stop

@section('auth_footer')
    @if (Route::has('password.request'))
        <p class="mb-1">
            <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
        </p>
    @endif
    @if (Route::has('register'))
        <p class="mb-0">
            <a href="{{ route('register') }}" class="text-center">{{ __('Register a new membership') }}</a>
        </p>
    @endif
@stop