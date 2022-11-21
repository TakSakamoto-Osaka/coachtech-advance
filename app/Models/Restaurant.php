<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 店舗モデルクラス
 */
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

    /**
     * 
     * 検索条件に応じて店舗データを取得する
     * 
     * @param mixed $selected_cond
     * 
     * @return [type]
     */
    public static function getRestaurantData( $selected_cond, $user = null, $favorite = false )
    {
        try {
            $results = DB::table('restaurants as r')
                ->select('r.id', 'r.name', 'g.name as genre_name', 'a.name as area_name', 'ri.img')
                ->Join('genres as g', 'r.genre_id', '=', 'g.id')
                ->Join('areas as a', 'r.area_id', '=', 'a.id')
                ->Join('restaurant_images as ri', 'r.id', '=', 'ri.restaurant_id');

            if ( $favorite == false ) {                 //  お気に入り一覧ではない場合
                //  エリア条件の指定がある場合、エリアで絞る
                if ( $selected_cond['area'] != 0 ) {
                    $results = $results->where('a.id', '=', $selected_cond['area']);
                }
                    
                //  ジャンル条件の指定がある場合、ジャンルで絞る
                if ( $selected_cond['genre'] != 0 ) {
                    $results = $results->where('g.id', '=', $selected_cond['genre']);
                }
                    
                //  店名条件の指定がある場合、店名の部分一致で絞る
                if ( $selected_cond['name'] != "" ) {
                    $results = $results->where('r.name', 'like', "%{$selected_cond['name']}%");
                }
            } else {
                $results = $results->Join('favorites as f', 'f.restaurant_id', '=', 'r.id')
                                    ->where('f.user_id', '=', $user->id);
            }
                
            //  組み立てたクエリーを実行
            $results = $results->where('ri.order', 1)->orderBy('r.id')->get();
                
            if ( $user !== null ) {     //  ユーザーが指定されている場合(マイページの場合)
                foreach( $results as $restaurant ) {
                    $favorite = Favorite::Where('user_id', '=', $user->id)->Where('restaurant_id', '=', $restaurant->id)->get();

                    if ( count( $favorite ) > 0 ) {     //  お気に入りの店舗だった場合
                        $restaurant->favorite = true;
                    } else {
                        $restaurant->favorite = false;
                    }
                }
            }

            return( $results );

        } catch( Exception $e ) {
            throw $e;
        }
    }

    /**
     * 
     * 店舗詳細データ取得
     * 
     * @param mixed $id     店舗ID
     * 
     * @return [type]       店舗情報, 店舗画像
     */
    public static function getDetail( $id )
    {
        try {
            $restaurant = DB::table('restaurants as r')
                ->select('r.id', 'r.name', 'r.info', 'g.name as genre_name', 'a.name as area_name')
                ->Join('genres as g', 'r.genre_id', '=', 'g.id')
                ->Join('areas as a', 'r.area_id', '=', 'a.id')
                ->where('r.id', '=', $id)
                ->first();

            $images = DB::table('restaurant_images as ri')
                ->select('ri.order', 'ri.img')
                ->where('ri.restaurant_id', '=', $id)
                ->orderBy('ri.order')
                ->get();

            return( array( $restaurant, $images ) );

        } catch( Exception $e ) {
            throw $e;
        }
    }

    /**
     * 
     * 店舗が存在するエリアを取得する
     * 
     * @return [type]
     */
    public static function getUsingAreas()
    {
        try {
            $areas = DB::table('restaurants as r')
                ->select('r.area_id as id', 'a.name as name')
                ->Join('areas as a', 'r.area_id', '=', 'a.id')
                ->distinct()
                ->orderBy('r.area_id')
                ->get();

            return( $areas );

        } catch( Exception $e ) {
            throw $e;
        }
    }
}
