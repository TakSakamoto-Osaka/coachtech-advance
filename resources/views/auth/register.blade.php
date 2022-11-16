@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')
<link href="{{ asset('/css/register.css') }}" rel="stylesheet">
@endsection

<!-- ページコンテンツ部 -->
@section('content')

<!-- Session Status -->
<div class="register-outer" :status="session('status')" />
    <form class="register-form" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="register-title">新規登録</div>
        
        <!-- User Name -->
        <div class="name-block">
            <div class="input-title">お名前</div>
            <input id="name" class="name-input" type="text" name="name" :value="old('name')" required autofocus />
            <p class="error">&nbsp;</p>
        </div>

        <!-- Email Address -->
        <div class="email-block">
            <div class="input-title">メールアドレス</div>
            <input id="email" class="email-input" type="email" name="email" :value="old('email')" required />
            <p class="error"> @if($errors->has('email')) {{ $errors->first('email') }} @else &nbsp;  @endif </p>
        </div>

        <!-- Password -->
        <div class="password-block">
            <div class="input-title">パスワード</div>
            <input id="password" class="password-input"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            <p class="error">&nbsp;</p>
        </div>

        <!-- Password -->
        <div class="password-block">
            <span class="input-title">パスワード[確認]</span>
            <input id="password_confirmation" class="password-input"
                                type="password"
                                name="password_confirmation"
                                required />
            <p class="error"> @if($errors->has('password')) {{ $errors->first('password') }} @else &nbsp;  @endif </p>
        </div>

        <div class="foot">
            <a href="{{ asset('/login') }}">既に登録されている場合</a>
            <button class="register-btn">登録</button>
        </div>
    </form>
</div>

@endsection
