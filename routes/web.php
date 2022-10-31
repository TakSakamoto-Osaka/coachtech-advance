<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Restaurant\RestaurantController;

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

//  サイトトップ
Route::get('/', [RestaurantController::class, 'index']);   //  店舗ページTOP

//  飲食店関連ページ
Route::get('/detail/{id}', [RestaurantController::class, 'detail']);    //  店舗詳細ページ

// Route::prefix('restaurant')->group( function () {
//   Route::get('', [RestaurantController::class, 'index']);   //  店舗ページTOP
// });


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/auth.php';
