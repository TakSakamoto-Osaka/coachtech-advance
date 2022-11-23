@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')
<link href="{{ asset('/css/restaurant.css') }}" rel="stylesheet">
@endsection


<!-- ページコンテンツ部 -->
@section('content')

<div class="outer">
  <div class="inner">
    <!-- トップページ -->
    <div class="header">
      <div class="header-title">Rese サイト管理</div>

        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <a onclick="event.preventDefault();this.closest('form').submit();">ログアウト</a>
        </form>
    </div>
  </div>
</div>

@endsection
