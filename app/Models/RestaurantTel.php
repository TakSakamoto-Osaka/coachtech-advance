<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 店舗連絡先モデルクラス
 */
class RestaurantTel extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',            //  店舗ID
        'tel',                      //  電話番号
        'type',                     //  電話種類 1 : 通常 / 2 : フリーダイヤル / 3 : 携帯
    ];
}
