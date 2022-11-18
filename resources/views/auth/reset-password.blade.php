@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')
<link href="{{ asset('/css/reset-password.css') }}" rel="stylesheet">
@endsection

<!-- ページコンテンツ部 -->
@section('content')

<div class="reset-password-outer" :status="session('status')" />
    <form class="reset-password-form" method="POST" action="{{ route('password.update') }}">
        @csrf
        <div class="reset-password-title">パスワードリセット</div>

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="email-block">
            <div class="input-title">メールアドレス</div>
            <input id="email" class="email-input" type="email" name="email" :value="old('email')" required />
            <p class="error"> @if($errors->has('email')) {{ $errors->first('email') }} @else &nbsp;  @endif </p>
        </div>

        <!-- Password -->
        <div class="password-block">
            <div class="input-title">パスワード</div>
            <input id="password" class="password-input" type="password" name="password" required  />
            <p class="error">&nbsp;</p>
        </div>

        <!-- Confirm Password -->
        <div class="password-block">
            <span class="input-title">パスワード[確認]</span>
            <input id="password_confirmation" class="password-input" type="password" name="password_confirmation" required />
            <p class="error"> @if($errors->has('password')) {{ $errors->first('password') }} @else &nbsp;  @endif </p>
        </div>

        <div class="foot">
            <button class="reset-password-btn">パスワードリセット</button>
        </div>
    </form>
</div>

@endsection