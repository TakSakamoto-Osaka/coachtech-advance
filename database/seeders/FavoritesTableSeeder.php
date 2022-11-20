<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Favorite;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            Schema::disableForeignKeyConstraints();         //  外部キーチェックを無効にする
            Favorite::truncate();                           //  既存データ全件削除
            Schema::enableForeignKeyConstraints();          //  外部キーチェックを有効にする

            $users       = User::get();                     //  全ユーザーデータ取得
            $restaurants = Restaurant::get();               //  全店舗データ取得
            $num         = count( Restaurant::get() );      //  全店舗数取得

            foreach ( $users as $user ) {
                foreach( $restaurants as $restaurant ) {
                    if ( random_int( 1, 10 ) <= 3 ) {       //  3割の確率でお気に入りに入れる
                        $param = [
                            'user_id'       => $user->id,           //  ユーザー名
                            'restaurant_id' => $restaurant->id,     //  メールアドレス
                        ];
            
                        Favorite::create( $param );                 //  レコード追加
                    }
                }
            }

        } catch ( Exception $e ) {
            echo $e->getMessage(), "\n";
        }
    }
}
