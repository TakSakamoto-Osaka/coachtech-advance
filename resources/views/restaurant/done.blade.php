@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')

<link href="{{ asset('/css/done.css') }}" rel="stylesheet">
@endsection


<!-- ページコンテンツ部 -->
@section('content')

<div class="outer">
  <div class="inner">
    <div class="done-content">
      <p class="done-message">予約が完了しました。</p>

      <div class="foot">
        <button class="btn-done" onclick="location.href='{{ asset('/mypage') }}'">一覧に戻る</button>
      </div>
    </div>
  </div>
</div>

@endsection
