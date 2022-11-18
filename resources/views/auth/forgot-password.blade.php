@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')
<link href="{{ asset('/css/forgot-password.css') }}" rel="stylesheet">
@endsection

<!-- ページコンテンツ部 -->
@section('content')

<!-- Session Status -->
<div class="forgot-password-outer" />
    <div class="forgot-password-inner">
        <div class="forgot-password-message">
            パスワードを再設定する場合、下記メールアドレス欄にメールアドレスを入力し、『 パスワードリセットボタン』を押下してください 
        </div>

        <!-- Session Status -->
        <div :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="email-block">
                <div class="input-title">メールアドレス</div>
                <input id="email" class="email-input" type="email" name="email" :value="old('email')" required autofocus/>
            </div>
            <p class="error"> @if($errors->has('email')) {{ $errors->first('email') }} @else &nbsp;  @endif </p>

            <button class="forgot-password-btn">
                パスワードリセット
            </button>
        </form>
    </div>

</div>

@endsection



