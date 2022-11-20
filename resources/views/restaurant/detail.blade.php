@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')

<link rel="stylesheet" href="{{ asset('/css/slick/slick.css') }}">
<link rel="stylesheet" href="{{ asset('/css/slick/slick-theme.css') }}">
<script src="{{ asset('/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

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

        <div class="img-container">
          <div class="slider">
          @foreach ( $images as $image )
              <div class="slick-img">
                <img src="{{ $image }}" alt="">
              </div>
              @endforeach
          </div>
          
          <div class="thumbnail">
            @foreach ( $images as $image )
              <div class="thumnail-img">
                <img src="{{ $image }}" alt="">
              </div>
              @endforeach
          </div>
        </div>
        
        
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

<!-- JavaScript部 -->
@section('script')
    $(".slider").slick({
      autoplay: false, // 自動再生OFF
      arrows: false, // 矢印非表示
      asNavFor: ".thumbnail", // サムネイルと同期
    });

    // サムネイルのオプション
    $(".thumbnail").slick({
      @if ( count( $images ) > 5 )
        slidesToShow: 5, // サムネイルの表示数
      @else
        slidesToShow: {{ count( $images ) }}, // サムネイルの表示数
      @endif
      
      asNavFor: ".slider", // メイン画像と同期
      focusOnSelect: true, // サムネイルクリックを有効化
    });
@endsection
