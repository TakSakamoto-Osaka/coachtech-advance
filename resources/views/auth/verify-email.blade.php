@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')
<link href="{{ asset('/css/verify-email.css') }}" rel="stylesheet">
@endsection

<!-- ページコンテンツ部 -->
@section('content')

<!-- Session Status -->
<div class="verify-email-outer" />
    <div class="verify-email-inner">
        <div class="verify-email-message">
            ご登録いただきありがとうございます。本サイトにログインする前に先ほど入力されたメールアドレスに送付された本登録用メールのリンクをクリックし、
            登録を完了させてください。もし本登録用メールが届いていない場合、下記の『 本登録メール再送信 』ボタンをクリックしてください。
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="verify-email-link-sent">
                本登録用メールを再送信しました。
            </div>
        @endif

        <div class="foot">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="resend-email-btn">本登録メール再送信</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="logout" href="{{ asset('/logout')}}" onclick="event.preventDefault();this.closest('form').submit();">ログアウト</a>
            </form>

        </div>
    </div>

</div>

@endsection
