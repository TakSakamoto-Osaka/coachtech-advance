@extends("layouts.default")

<!-- ページ固有CSS -->
@section('pageCSS')


<link href="{{ asset('/css/thanks.css') }}" rel="stylesheet">
@endsection

<!-- ページコンテンツ部 -->
@section('content')

<div class="outer">
  <div class="inner">
    <div class="thanks-content">
      <p class="thanks-message">下記内容の予約を削除しますか？</p>
        <div class="confirm-content">
          <div class="reserve-title">店名</div>
          <div class="reserve-content">{{$reserve_contents['restaurant_name']}}</div>
        </div>

        <div class="confirm-content">
          <div class="reserve-title">予約日</div>
          <div class="reserve-content">{{ $reserve_contents['date'] }}</div>
        </div>

        <div class="confirm-content">
          <div class="reserve-title">時間</div>
          <div class="reserve-content">{{ substr( $reserve_contents['time'], 0, -3 ) }}</div>
        </div>

        <div class="confirm-content">
          <div class="reserve-title">人数</div>
          <div class="reserve-content">{{$reserve_contents['number']}}人</div>
        </div>

      <div class="foot">
        <button class="delete-cancel" onclick="location.href='{{ asset('/detail').'/'.$reserve_contents['restaurant_id'] }}'">削除せずに戻る</button>
        <button class="delete-fix" onclick="location.href='{{ asset('/del_done') }}'">予約削除確定</button>
      </div>
    </div>
  </div>
</div>

@endsection