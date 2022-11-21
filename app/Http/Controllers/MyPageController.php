<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use App\Models\Restaurant;
use App\Models\Genre;

class MyPageController extends Controller
{
    use \App\Http\Trait\Restaurant;

    /**
     * 
     * マイページ表示処理
     * 
     * @param Request $request  リクエストオブジェクト
     * 
     * @return [type]
     */
    public function mypage( Request $request )
    {
        if ( $request->session()->exists('selected_cond') == true ) {    //  セッション中にキー'selected_cond'(検索条件)が存在する場合
            $selected_cond = $request->session()->get('selected_cond');           //  セッション中のselected_cond取得
        } else {
            $selected_cond = [
                'area'  => 0,       //  全エリア
                'genre' => 0,       //  全ジャンル
                'name'  => ''       //  店名指定なし
            ];
        }

        // 現在認証しているユーザーを取得
        $user = Auth::user();

        //  検索条件生成
        $search_cond = [
            'areas'  => Restaurant::getUsingAreas(),    //  店舗の存在する全エリア
            'genres' => Genre::getAll()                 //  全ジャンル
        ];

        //  条件を指定して店舗検索
        $restaurants = Restaurant::getRestaurantData( $selected_cond, $user );

        //  店舗情報に画像データ追加
        $restaurants = $this->addRestaurantImage( $restaurants );
        
        return view('restaurant.index', [ 'search_cond'=>$search_cond, 
                                        'user'=>$user,
                                        'selected_cond'=>$selected_cond,
                                        'restaurants'=>$restaurants,
                                        'favorite' => false]);
    }

    /**
     * 
     * 店舗検索処理
     * 
     * @param Request $request  リクエストオブジェクト
     * 
     * @return [type]
     */
    public function search( Request $request )
    {
        if ( $request->session()->exists('selected_cond') == true ) {       //  セッション中にキー'selected_cond'(検索条件)が存在する場合
            $selected_cond = $request->session()->get('selected_cond');     //  セッション中のselected_cond取得
        } else {
            $selected_cond = [
                'area'  => 0,       //  全エリア
                'genre' => 0,       //  全ジャンル
                'name'  => ''       //  店名指定なし
            ];
        }

        // 現在認証しているユーザーを取得
        $user = Auth::user();

        //  店舗の存在する全エリア情報取得
        $areas = Restaurant::getUsingAreas();

        //  全ジャンルを取得
        $genres = Genre::getAll();

        //  検索条件生成
        $search_cond = [
            'areas'  => $areas,
            'genres' => $genres
        ];

        //  条件を指定して店舗検索
        $restaurants = Restaurant::getRestaurantData( $selected_cond, $user );

        //  店舗情報に画像データ追加
        $restaurants = $this->addRestaurantImage( $restaurants, $user );
        
        return view('restaurant.index', [ 'search_cond'=>$search_cond, 
                                        'user'=>$user,
                                        'selected_cond'=>$selected_cond,
                                        'restaurants'=>$restaurants,
                                        'favorite' => false]);
    }

    /**
     * 
     * お気に入り一覧
     * 
     * @param Request $request  リクエストオブジェクト
     * 
     * @return [type]
     */
    public function favorite( Request $request )
    {
        if ( $request->session()->exists('selected_cond') == true ) {    //  セッション中にキー'selected_cond'(検索条件)が存在する場合
            $selected_cond = $request->session()->get('selected_cond');           //  セッション中のselected_cond取得
        } else {
            $selected_cond = [
                'area'  => 0,       //  全エリア
                'genre' => 0,       //  全ジャンル
                'name'  => ''       //  店名指定なし
            ];
        }

        // 現在認証しているユーザーを取得
        $user = Auth::user();

        //  検索条件生成
        $search_cond = [
            'areas'  => Restaurant::getUsingAreas(),    //  店舗の存在する全エリア
            'genres' => Genre::getAll()                 //  全ジャンル
        ];

        //  条件を指定して店舗検索
        $restaurants = Restaurant::getRestaurantData( $selected_cond, $user, true );

        //  店舗情報に画像データ追加
        $restaurants = $this->addRestaurantImage( $restaurants );
        
        return view('restaurant.index', [ 'search_cond'=>$search_cond, 
                                        'user'=>$user,
                                        'selected_cond'=>$selected_cond,
                                        'restaurants'=>$restaurants,
                                        'favorite' => true]);
    }
}
