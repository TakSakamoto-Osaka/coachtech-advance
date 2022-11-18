<?php
namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $cmd = "aws s3 presign s3://aws-sakamoto-test-coachtech/images/restaurant-00001-001.jpeg";
            exec($cmd, $opt);
            echo $opt;
            $restaurant->img = $opt[0];
        }
        
        return view('restaurant.index', ['user'=>$user, 'restaurants'=>$restaurants]);
    }

    public function detail( Request $request, $id )
    {
        list( $restaurant, $images ) = Restaurant::getDetail( $id );

        return view('restaurant.detail', ['restaurant'=>$restaurant, 'images'=>$images]);
    }
}
