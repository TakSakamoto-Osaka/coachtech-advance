<?php
namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RestaurantController extends Controller
{

    public function index( Request $request )
    {
        // 現在認証しているユーザーを取得
        $user = Auth::user();

        // 現在認証しているユーザーのIDを取得
        $id = Auth::id();
        
        return view('restaurant.index', ['user'=>$user]);
    }
}
