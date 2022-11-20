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
Route::get('/', [RestaurantController::class, 'index']);


//  承認・認証されている場合のみ
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/mypage', [MyPageController::class, 'mypage']);    //  店舗詳細ページ
    Route::get('/detail/{id}', [RestaurantController::class, 'detail']);    //  店舗詳細ページ
});

require __DIR__.'/auth.php';
