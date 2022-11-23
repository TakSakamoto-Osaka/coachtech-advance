<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use App\Models\Restaurant;
use App\Models\Genre;
use App\Models\Reserve;

class AdminController extends Controller
{
    /**
     * 
     * サイト管理者ページ
     * 
     * @param Request $request  リクエストオブジェクト
     * 
     * @return [type]
     */
    public function admin( Request $request )
    {


        return view('admin.index');
    }

}
