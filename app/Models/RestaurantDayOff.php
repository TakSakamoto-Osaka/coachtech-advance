<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantDayOff extends Model
{
    use HasFactory;

    protected $table = "restaurant_dayoffs";

    protected $fillable = [
        'restaurant_id',            //  店舗ID
        'day_off_week',             //  1: 日曜日 / 2 : 月曜日 / ・・・　/ 7 : 土曜日(SQLのdayofweek関数の値)
    ];
}
