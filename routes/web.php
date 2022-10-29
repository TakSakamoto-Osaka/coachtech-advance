<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guest\GuestController;

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
// Route::get('/', function () {
//     return view('welcome');
// });
Route::redirect('/', '/guest');

//  ゲスト関連ページ
Route::prefix('guest')->group( function () {
  Route::get('/', [GuestController::class, 'index']);         //  ゲストTOPページ
});
// Route::middleware(['auth', 'verified'])->prefix('guest')->group( function () {
//   Route::get('/', [GuestController::class, 'index']);         //  ゲストTOPページ
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/auth.php';
