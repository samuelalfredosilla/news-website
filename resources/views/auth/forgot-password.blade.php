@extends('adminlte::auth.passwords.email') {{-- Gunakan ini agar layout-nya sesuai AdminLTE --}}

@section('auth_header', __('Forgot Your Password?')) {{-- Judul Halaman --}}

@section('auth_body')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   placeholder="{{ __('Email') }}" value="{{ old('email') }}" required autofocus>
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

        <button type="submit" class="btn btn-primary btn-block">
            {{ __('Send Password Reset Link') }}
        </button>
    </form>
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ route('login') }}">{{ __('Back to login') }}</a>
    </p>
@stop