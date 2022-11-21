<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 予約情報モデルクラス
 */
class Reserve extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',                  //  ユーザーID
        'restaurant_id',            //  店舗ID
        'number',                   //  利用人数
        'reserve_date',             //  予約日
        'start_time',               //  開始時間
    ];


    /**
     * 
     * 指定した予約情報を取得する
     * 
     * @param mixed $user_id
     * @param mixed $restaurant_id
     * @param mixed $date
     * @param mixed $time
     * 
     * @return [type]
     */
    public static function getReserveData( $user_id, $restaurant_id, $date, $time )
    {
        $reserve = Reserve::where('user_id', '=', $user_id)->where('restaurant_id', '=', $restaurant_id)
                ->where('reserve_date', '=', $date)->where('start_time', '=', $time)
                ->first();

        return( $reserve );
    }

    /**
     * 
     * 指定したユーザーの指定店舗の予約情報を取得する
     * 
     * @param mixed $user_id
     * @param mixed $restaurant_id
     * 
     * @return [type]
     */
    public static function getReserveAllData( $user_id, $restaurant_id )
    {
        $reserve = Reserve::where('user_id', '=', $user_id)->where('restaurant_id', '=', $restaurant_id)
                ->get();

        return( $reserve );
    }
}
