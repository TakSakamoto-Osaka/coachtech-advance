<?php
namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use App\Models\Restaurant;

class RestaurantController extends Controller
{

    public function index( Request $request )
    {
        //  全ての店舗データを取得
        $restaurants = Restaurant::getAll();

        // 現在認証しているユーザーを取得
        $user = Auth::user();

        // 現在認証しているユーザーのIDを取得
        $id = Auth::id();

        foreach ( $restaurants as $restaurant ) {
            //  デプロイ先に応じて画像ファイルの取得場所変更
            if ( App::environment('local') ) {          //  ローカル実行の場合
                $restaurant->img = asset('/storage/images').'/'.$restaurant->img;

            } elseif(App::environment('aws')) {         //  AWS S3の場合
                $str_id = sprintf('%05d', $restaurant->id);     //  レストランIDの5桁数値文字列

                $cmd = "aws s3 presign s3://aws-sakamoto-test-coachtech/images/restaurant-{$str_id}-001.jpeg";
                exec($cmd, $opt);
                echo $opt;
                $restaurant->img = $opt[0];

            } else {                                    //  その他(Heroku等)       
                $restaurant->img = asset('/storage/images').'/'.$restaurant->img;
            }

        }
        
        
        return view('restaurant.index', ['user'=>$user, 'restaurants'=>$restaurants]);
    }

    public function detail( Request $request, $id )
    {
        list( $restaurant, $images ) = Restaurant::getDetail( $id );

        return view('restaurant.detail', ['restaurant'=>$restaurant, 'images'=>$images]);
    }
}
