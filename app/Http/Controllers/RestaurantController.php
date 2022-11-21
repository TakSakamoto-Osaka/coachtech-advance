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
use App\Models\Favorite;

class RestaurantController extends Controller
{
    use \App\Http\Trait\Restaurant;

    /**
     * 
     * 店舗一覧画面
     * 
     * @param Request $request  リクエストオブジェクト
     * 
     * @return [type]           店舗一覧画面 Bladeビュー
     */
    public function index( Request $request )
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

        if ( $user !== null ) {     //  認証されたユーザーがアクセスしている場合
            return redirect('/mypage');             //  マイページへリダイレクトする
        }

        //  検索条件生成
        $search_cond = [
            'areas'  => Restaurant::getUsingAreas(),    //  店舗の存在する全エリア
            'genres' => Genre::getAll()                 //  全ジャンル
        ];

        //  条件を指定して店舗検索
        $restaurants = Restaurant::getRestaurantData( $selected_cond );

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
     * 店舗詳細画面
     * 
     * @param Request $request  リクエストオブジェクト
     * @param mixed $id         店舗ID
     * 
     * @return [type]           店舗詳細画面 Bladeビュー
     */
    public function detail( Request $request, $id )
    {
        list( $restaurant, $images ) = Restaurant::getDetail( $id );

        //  デプロイ先に応じて画像ファイルの取得場所変更
        $images = $this->getImagePath( $images );
        
        // 現在認証しているユーザーを取得
        $user = Auth::user();
        
        //  お気に入り検索
        $favorite = false;

        if ( $user !== null ) {     //  マイページからの表示の場合
            $favorite = Restaurant::isFavorite( $restaurant->id, $user->id );   //  現在のお気に入り情報

            //  お気に入り変更パラメータ取得
            if ( $request->favorite !== null  ) {
                if ( $request->favorite === 'on' ) {
                    $favorite = true;
                    //  データ追加
                    $obj = new Favorite();
                    $obj->create([
                        'user_id'       => $user->id,
                        'restaurant_id' => $restaurant->id
                    ]);

                } else {
                    $favorite = false;

                    //  データ削除
                    Favorite::where('user_id', '=', $user->id)->where('restaurant_id', '=', $restaurant->id)->delete();
                }
            }
        } 

        return view('restaurant.detail', ['restaurant'=>$restaurant, 'images'=>$images,
                                        'favorite'=>$favorite, 'user'=>$user]);
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
        $form = $request->all();                    //  フォームプロパティ全て取得(検索条件)

        $selected_cond = [
            'area'  => $form['area'],           //  エリア
            'genre' => $form['genre'],          //  ジャンル
            'name'  => $form['name']            //  店名
        ];

        $request->session()->put('selected_cond', $selected_cond);    //  検索条件をセッションに保存

        // 現在認証しているユーザーを取得
        $user = Auth::user();

        if ( $user !== null ) {                 //  認証されたユーザーがアクセスしている場合
            return redirect('/mypage/search');             //  マイページ/検索へリダイレクトする
        }

        //  検索条件生成
        $search_cond = [
            'areas'  => Restaurant::getUsingAreas(),    //  店舗の存在する全エリア
            'genres' => Genre::getAll()                 //  全ジャンル
        ];

        //  条件を指定して店舗検索
        $restaurants = Restaurant::getRestaurantData( $selected_cond );

        //  店舗情報に画像データ追加
        $restaurants = $this->addRestaurantImage( $restaurants );
        
        return view('restaurant.index', [ 'search_cond'=>$search_cond, 
                                        'user'=>$user,
                                        'selected_cond'=>$selected_cond,
                                        'restaurants'=>$restaurants,
                                        'favorite' => false]);
    }
}
