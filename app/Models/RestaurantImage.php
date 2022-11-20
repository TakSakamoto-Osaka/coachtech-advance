<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 店舗画像クラス
 */
class RestaurantImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',            //  店舗ID
        'order',                    //  表示順(1の場合代表画像, 0は画像無効フラグ)
        'img',                      //  画像
    ];
}
