<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MyPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//  ゲスト可
Route::get('/', [RestaurantController::class, 'index']);                        //  TOPページ
Route::post('/', [RestaurantController::class, 'search'])->name('search');      //  店舗検索
Route::get('/detail/{id}', [RestaurantController::class, 'detail']);            //  店舗詳細ページ

//  承認・認証されている場合のみ
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/mypage',               [MyPageController::class, 'mypage']);       //  マイページ
    Route::get('/mypage/search',        [MyPageController::class, 'search']);       //  店舗検索
    Route::get('/mypage/favorite',      [MyPageController::class, 'favorite']);     //  お気に入り一覧

    Route::post('/mypage/reserve',      [MyPageController::class, 'reserve']);      //  予約
    Route::get('/thanks',               [MyPageController::class, 'thanks']);       //  サンクスページ(予約内容確認)
    Route::get('/done',                 [MyPageController::class, 'done']);         //  予約完了
    
    Route::get('/del_thanks',           [MyPageController::class, 'delThanks']);    //  削除サンクスページ(削除内容確認)
    Route::get('/del_done',             [MyPageController::class, 'delDone']);      //  削除完了
});

require __DIR__.'/auth.php';
