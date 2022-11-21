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
    
    <div class="search-and-favorite">
      @if ( $favorite === false )
      <form class="form-search" method="POST" action="{{ route('search') }}"">
        @csrf
        <fieldset class="search-fieldset">
          <legend class="search-legent">検索条件</legend>
          <div class="search-block">
            <div class="select-area-block">
              <p class="select-area-title">エリア</p>
              <select name="area" id="area" class="select-area">
                <option value="0">選択なし</option>
                @foreach ( $search_cond['areas'] as $area )
                  @if ( $selected_cond['area'] == $area->id )
                    <option value="{{ $area->id }}" selected>{{ $area->name }}</option>
                  @else
                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                  @endif
                @endforeach
              </select>
            </div>

            <div class="select-genre-block">
              <p class="select-genre-title">ジャンル</p>
              <select name="genre" id="genre" class="select-genre">
                <option value="0">選択なし</option>
                @foreach ( $search_cond['genres'] as $genre )
                  @if ( $selected_cond['genre'] == $genre->id )
                    <option value="{{ $genre->id }}" selected>{{ $genre->name }}</option>
                  @else
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                  @endif
                @endforeach
              </select>
            </div>

            <div class="search-name-block">
              <p class="search-name-title">店名</p>
              <input name="name" type="text" class="input-restaurant-name" value={{ $selected_cond['name'] }}>
            </div>
  
            <button class="btn-search">検索</button>
          </div>
        </fieldset>
      </form>

      @if ( $user !== null )
        <button class="btn-favorite-restaurants" onclick="location.href='{{ asset('/mypage/favorite')}}'">お気に入り一覧</button>
      @endif

      @else
        <button class="btn-back" onclick="history.back()">戻る</button>
      @endif
    </div>

    <div class="card-set">
      @foreach ( $restaurants as $restaurant )
        <div class="card">
          <img class="img-restaurant" src="{{ $restaurant->img }}" alt="">
          
          <div class="restaurant-item">
            <p class="restaurant-name">{{ $restaurant->name }}</p>
            <span class="hash_tag">#{{ $restaurant->area_name }}</span>
            <span class="hash_tag">#{{ $restaurant->genre_name }}</span>
            <div class="card-bottom">
              <button type=“button” class="btn-detail" onclick="location.href='{{ asset('/detail') }}/{{ $restaurant->id }}'">詳しくみる</button>
              @if ( $user !== null )
                @if ( $restaurant->favorite === true )
                  <img class="img-favorite" src="{{ asset('/img/Heart-ON.jpeg') }}" alt="">
                @else
                  <img class="img-favorite" src="{{ asset('/img/Heart-OFF.jpeg') }}" alt="">
                @endif
              @endif

            </div>
          </div>
        </div>
      @endforeach
    </div>

  </div>
</div>

@endsection
