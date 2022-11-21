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
        <p class="header-title"><a href="{{ asset('/') }}">Rese</a></p>

        <div class="name-and-favorite-block">
          <p class="restaurant-name">{{ $restaurant->name }}</p>
          
          @if ( $user !== null )
            @if ( $favorite === true )
              <img class="img-favorite" src="{{ asset('/img/Heart-ON.jpeg') }}" alt="" onclick="location.href='{{ asset('/detail').'/'.$restaurant->id.'/?favorite=off' }}'">
            @else
              <img class="img-favorite" src="{{ asset('/img/Heart-OFF.jpeg') }}" alt="" onclick="location.href='{{ asset('/detail').'/'.$restaurant->id.'/?favorite=on' }}'">
            @endif
          @endif
        </div>

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
        
        <button class="btn-back" onclick="location.href='{{ asset('/') }}'">戻る</button>
      </div>

      <div class="reserve-area">
        @if ( $cur_reserve === null )
        <p class="reserve-title">予約</p>

        <form method=POST action="{{ asset('/mypage/reserve') }}">
          @csrf
          <input type="text" name="restaurant-id" hidden value={{ $restaurant->id }}>

          @if ( $reserve_contents !== null )
            <input class="input-reserve-date" name="date" type="date" value="{{ $reserve_contents['date'] }}" min="{{ $today }}" max="{{ $maxday }}">
          @else
            <input class="input-reserve-date" name="date" type="date" value="{{ $today }}" min="{{ $today }}" max="{{ $maxday }}">
          @endif

          <select class="select-time" name="time" id="select-time">
            @if ( $reserve_contents !== null )
              @if ( $reserve_contents['time'] == "17:00" ) <option value="17:00" selected>17:00</option> @else <option value="17:00">17:00</option> @endif
              @if ( $reserve_contents['time'] == "17:30" ) <option value="17:30" selected>17:30</option> @else <option value="17:30">17:30</option> @endif
              @if ( $reserve_contents['time'] == "18:00" ) <option value="18:00" selected>18:00</option> @else <option value="18:00">18:00</option> @endif
              @if ( $reserve_contents['time'] == "18:30" ) <option value="18:30" selected>18:30</option> @else <option value="18:30">18:30</option> @endif
              @if ( $reserve_contents['time'] == "19:00" ) <option value="19:00" selected>19:00</option> @else <option value="19:00">19:00</option> @endif
              @if ( $reserve_contents['time'] == "19:30" ) <option value="19:30" selected>19:30</option> @else <option value="19:30">19:30</option> @endif
              @if ( $reserve_contents['time'] == "20:00" ) <option value="20:00" selected>20:00</option> @else <option value="20:00">20:00</option> @endif

            @else
            <option value="17:00">17:00</option>
            <option value="17:30">17:30</option>
            <option value="18:00">18:00</option>
            <option value="18:30">18:30</option>
            <option value="19:00">19:00</option>
            <option value="19:30">19:30</option>
            <option value="20:00">20:00</option>
            @endif
          </select>

          <select class="select-number" name="number" id="select-number">
            @if ( $reserve_contents !== null )
              @if ($reserve_contents['number'] == "1") <option value="1" selected>１人</option> @else <option value="1">１人</option> @endif
              @if ($reserve_contents['number'] == "2") <option value="2" selected>２人</option> @else <option value="2">２人</option> @endif
              @if ($reserve_contents['number'] == "3") <option value="3" selected>３人</option> @else <option value="3">３人</option> @endif
              @if ($reserve_contents['number'] == "4") <option value="4" selected>４人</option> @else <option value="4">４人</option> @endif
              @if ($reserve_contents['number'] == "5") <option value="5" selected>５人</option> @else <option value="5">５人</option> @endif
              @if ($reserve_contents['number'] == "6") <option value="6" selected>６人</option> @else <option value="6">６人</option> @endif
              @if ($reserve_contents['number'] == "7") <option value="7" selected>７人</option> @else <option value="7">７人</option> @endif
              @if ($reserve_contents['number'] == "8") <option value="8" selected>８人</option> @else <option value="8">８人</option> @endif
            @else
            <option value="1">１人</option>
            <option value="2">２人</option>
            <option value="3">３人</option>
            <option value="4">４人</option>
            <option value="5">５人</option>
            <option value="6">６人</option>
            <option value="7">７人</option>
            <option value="8">８人</option>
            @endif
          </select>
  
          <button class="btn-reserve">予約する</button>
        </form>

        @else
          <p class="reserve-title">現在の予約内容</p>

          <form method=GET action="{{ asset('/del_thanks') }}">
            @csrf
            <input type="text" name="restaurant-id" hidden value={{ $restaurant->id }}>

            <div class="confirm-content">
              <div class="cur-reserve-title">予約日</div>
              <div class="cur-reserve-content">{{ $reserve_contents['date'] }}</div>
            </div>

            <div class="confirm-content">
              <div class="cur-reserve-title">時間</div>
              <div class="cur-reserve-content">{{ substr( $reserve_contents['time'], 0, -3 ) }}</div>
            </div>

            <div class="confirm-content">
              <div class="cur-reserve-title">人数</div>
              <div class="cur-reserve-content">{{ $reserve_contents['number'] }}人</div>
            </div>

            <button class="btn-delete">予約削除</button
          </form>

        @endif

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
