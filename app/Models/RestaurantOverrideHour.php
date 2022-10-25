<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantOverrideHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',            //  店舗ID
        'special_day',              //  臨時日
        'temporary_type',           //  1 : 臨時営業 / 2 : 臨時休日
        'type',                     //  1 : 昼間 / 2 : 夜
        'open_time',                //  開店時間
        'close_time',               //  閉店時間
        'lastorder_time',           //  ラストオーダー時間 (この時間以降は予約不可)
    ];
}
