<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',                 //  店舗名
        'genre_id',             //  ジャンルID
        'info',                 //  店舗情報
        'area_id',              //  地域ID
        'address',              //  店舗住所
        'reserve_max_day',      //  何日先の予約まで可能かの日数
        'closed_day',           //  閉店(予定)日 : この日の翌日以降は予約不可
    ];
}
