@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')

@endsection


<!-- ページコンテンツ部 -->
@section('content')

<!-- トップページ -->
<div>
トップページ表示
</div>

@if ( $user !== null )
  <p>ログインユーザー：{{ $user['name'] }}</p>
@else
  <p>ゲスト</p>
@endif

<form method="POST" action="{{ route('logout') }}">
  @csrf
  <a href="/logout" onclick="event.preventDefault();this.closest('form').submit();">ログアウト</a>
</form>




@endsection

