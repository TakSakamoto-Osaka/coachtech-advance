@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')
<link href="{{ asset('/css/restaurant-detail.css') }}" rel="stylesheet">
@endsection


<!-- ページコンテンツ部 -->
@section('content')

<div class="outer">
  <div class="inner">
    <div class="detail-content">
      <div class="restaurant-info">
        <p class="header-title">Rese</p>

        <p class="restaurant-name">{{ $restaurant->name }}</p>
        <img src="data:image/jpeg;base64,{{ $images[0]->img }}" alt="">
        <div class="restaurant-attr">
          <span>#{{ $restaurant->area_name }}</span>
          <span>#{{ $restaurant->genre_name }}</span>
        </div>
        <p class="restaurant-explain">{{ $restaurant->info }}</p>
  
      </div>

    </div>

  </div>
</div>

@endsection

