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
      <div class="header-title">Rese</div>
    
      <div class="header-user">
        @if ( $user !== null )
          <p>ログインユーザー：{{ $user['name'] }}</p>
        @else
          <p>ゲスト</p>
        @endif

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          @if ( $user !== null )
            <a href="{{ asset('/logout')}}" onclick="event.preventDefault();this.closest('form').submit();">ログアウト</a>
            @else
            <a href="{{ asset('/logout')}}" onclick="event.preventDefault();this.closest('form').submit();">ログイン</a>
          @endif          
        </form>
      </div>
    </div>

    <div class="card-set">
      @foreach ( $restaurants as $restaurant )
        <div class="card">
          
          <img src="{{ $restaurant->img }}" alt="">
          
          <div class="restaurant-item">
            <p class="restaurant-name">{{ $restaurant->name }}</p>
            <span>#{{ $restaurant->area_name }}</span>
            <span>#{{ $restaurant->genre_name }}</span>
            <div class="card-bottom">
              <button type=“button” class="btn-detail" onclick="location.href='{{ asset('/detail') }}/{{ $restaurant->id }}'">詳しくみる</button>
              @if ( $user !== null )
                <img src="{{ asset('/img/Heart-OFF.jpeg') }}" alt="">
              @endif

            </div>
          </div>
        </div>
      @endforeach
    </div>

  </div>
</div>

@endsection

