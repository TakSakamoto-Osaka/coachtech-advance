@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')
<link href="{{ asset('/css/login.css') }}" rel="stylesheet">
@endsection

<!-- ページコンテンツ部 -->
@section('content')

<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />


    <form class="login-form" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="login-title">Login</div>
        
        <!-- Email Address -->
        <div class="email-block">
            <img class="icon" src="{{ asset('/img/email.png') }}" alt="">
            <input id="email" class="email-input" type="email" name="email" :value="old('email')" placeholder="Email" required autofocus />
            <p class="error"> @if($errors->has('email')) {{ $errors->first('email') }} @else &nbsp;  @endif </p>
        </div>

        <!-- Password -->
        <div class="password-block">
            <img class="icon" src="{{ asset('/img/lock.png') }}" alt="">
            <input id="password" class="password-input"
                                type="password"
                                name="password"
                                placeholder="Password"
                                required autocomplete="current-password" />
            <p class="error"> @if($errors->has('password')) {{ $errors->first('password') }} @else &nbsp;  @endif </p>
        </div>

        <div class="foot">
            <button class="login-btn">ログイン</button>
        </div>
        <div class="forgot-password">
            <a href="{{ asset('/register') }}">新規登録</a>
            <a href="{{ asset('/forgot-password') }}">パスワードを忘れた方</a>
        </div>

    </form>


@endsection
