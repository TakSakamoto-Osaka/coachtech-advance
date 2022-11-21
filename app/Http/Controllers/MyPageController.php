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
use App\Models\Reserve;

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
        //  セッション中の予約情報消去
        $request->session()->forget('reserve_contents');

        //  セッション中の検索条件取得
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

        //  予約用情報生成

        
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
        //  セッション中の予約情報消去
        $request->session()->forget('reserve_contents');

        //  セッション中の検索条件取得
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

    /**
     * 
     * 予約処理
     * 
     * @param Request $request  リクエストオブジェクト
     * 
     * @return [type]
     */
    public function reserve( Request $request )
    {
        $form = $request->all();                //  フォームプロパティ全て取得(検索条件)

        //  予約内容
        $reserve_contents = [
            'date'          => $form['date'],           //  予約日
            'time'          => $form['time'],           //  開始時間
            'number'        => $form['number'],         //  人数
            'restaurant_id' => $form['restaurant-id']   //  店舗ID
        ];

        //  店舗情報取得
        $restaurant = Restaurant::where('id', '=', $reserve_contents['restaurant_id'])->first();
        $reserve_contents['restaurant_name'] = $restaurant->name;

        $request->session()->put('reserve_contents', $reserve_contents);    //  予約情報をセッションに保存

        return redirect('/thanks');                     //  サンクスページへ     
    }

    /**
     * 
     * 予約内容確認
     * 
     * @param Request $request  リクエストオブジェクト
     * 
     * @return [type]
     */
    public function thanks( Request $request )
    {
        if ( $request->session()->exists('reserve_contents') == true ) {        //  セッション中にキー'reserve_contents'(検索条件)が存在する場合
            $reserve_contents = $request->session()->get('reserve_contents');       //  セッション中のreserve_contents取得
        
            return view('restaurant.thanks', ['reserve_contents'=>$reserve_contents]);
        }
    }

    /**
     * 
     * 予約完了処理
     * 
     * @param Request $request  リクエストオブジェクト
     * 
     * @return [type]
     */
    public function done( Request $request )
    {
        if ( $request->session()->exists('reserve_contents') == true ) {        //  セッション中にキー'reserve_contents'(検索条件)が存在する場合
            $reserve_contents = $request->session()->get('reserve_contents');       //  セッション中のreserve_contents取得

            //  予約データ生成
            // 現在認証しているユーザーを取得
            $user = Auth::user();

            $param = [  'user_id'       => $user->id,
                        'restaurant_id' => $reserve_contents['restaurant_id'],
                        'number'        => $reserve_contents['number'],
                        'reserve_date'  => $reserve_contents['date'],
                        'start_time'    => $reserve_contents['time'],
                    ];
            Reserve::create($param);

            //  セッション中の予約情報消去
            //      この時点でセッション中の予約情報を消去しないと、done画面がリロードされた場合に
            //      同じ内容の予約が追加されてしまうため。
            $request->session()->forget('reserve_contents');

            return view('restaurant.done');
        } else {
            return redirect('/mypage');                     //  マイページへ   
        }
    }

    /**
     * 
     * 削除内容確認
     * 
     * @param Request $request  リクエストオブジェクト
     * 
     * @return [type]
     */
    public function delThanks( Request $request )
    {
        if ( $request->session()->exists('reserve_contents') == true ) {        //  セッション中にキー'reserve_contents'(検索条件)が存在する場合
            $reserve_contents = $request->session()->get('reserve_contents');       //  セッション中のreserve_contents取得
        
            return view('restaurant.delThanks', ['reserve_contents'=>$reserve_contents]);
        }
    }

    /**
     * 
     * 削除完了処理
     * 
     * @param Request $request  リクエストオブジェクト
     * 
     * @return [type]
     */
    public function delDone( Request $request )
    {
        if ( $request->session()->exists('reserve_contents') == true ) {        //  セッション中にキー'reserve_contents'(検索条件)が存在する場合
            $reserve_contents = $request->session()->get('reserve_contents');       //  セッション中のreserve_contents取得

            // 予約データ削除
            // 現在認証しているユーザーを取得
            $user = Auth::user();

            $reserve = Reserve::getReserveData( $user->id, $reserve_contents['restaurant_id'], $reserve_contents['date'], $reserve_contents['time'] );
            $reserve->delete();

            //  セッション中の予約情報消去
            //      この時点でセッション中の予約情報を消去しないと、done画面がリロードされた場合に
            //      同じ内容の予約を削除しようとしてしまうため
            $request->session()->forget('reserve_contents');

            return view('restaurant.delDone');

        } else {
            return redirect('/mypage');                     //  マイページへ   
        }
    }
}
