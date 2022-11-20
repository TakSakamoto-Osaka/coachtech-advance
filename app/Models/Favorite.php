<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * お気に入りモデルクラス
 */
class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',                  //  ユーザーID
        'restaurant_id',            //  店舗ID
    ];
}
