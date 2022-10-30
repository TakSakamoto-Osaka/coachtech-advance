@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection

<!-- ページコンテンツ部 -->
@section('content')

<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<div class="login-form">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>Login</div>
        
        <!-- Email Address -->
        <div class="email-block">
            <input id="email" class="email-input" type="email" name="email" :value="old('email')" required autofocus />
            <p>@if($errors->has('email')) {{ $errors->first('email') }} @endif</p>
        </div>

        <!-- Password -->
        <div class="password-block">
            <input id="password" class="password-input"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            <p>@if($errors->has('password')) {{ $errors->first('password') }} @endif</p>
        </div>

        <button class="login-btn">ログイン</button>

    </form>

</div>

@endsection
