@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')
<link href="{{ asset('/css/confirm-password.css') }}" rel="stylesheet">
@endsection

<!-- ページコンテンツ部 -->
@section('content')
<div class="confirm-password-outer" />
    <form class="confirm-password-form" method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="confirm-password-title">パスワード確認</div>
            
        <div class="confirm-password-message">
            操作を続ける場合はパスワードを入力し、確認ボタンを押してください。
        </div>

        <!-- Password -->
        <div class="password-block">
            <div class="input-title">パスワード</div>
            <input id="password" class="password-input" type="password" name="password" required autocomplete="current-password" />
            <p class="error">&nbsp;</p>
        </div>

        <button class="confirm-password-btn">
            確認
        </button>
    </form>
</div>

@endsection
