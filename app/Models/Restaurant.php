<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function getAll()
    {
        try {
            $results = DB::table('restaurants as r')
                ->select('r.id', 'r.name', 'g.name as genre_name', 'a.name as area_name', 'ri.img')
                ->Join('genres as g', 'r.genre_id', '=', 'g.id')
                ->Join('areas as a', 'r.area_id', '=', 'a.id')
                ->Join('restaurant_images as ri', 'r.id', '=', 'ri.restaurant_id')
                ->where('ri.order', 1)
                ->orderBy('r.id')
                ->get();

            return( $results );

        } catch( Exception $e ) {
            throw $e;
        }

    } 
}
