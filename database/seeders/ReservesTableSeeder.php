<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Reserve;

class ReservesTableSeeder extends Seeder
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
            Reserve::truncate();                            //  既存データ全件削除
            Schema::enableForeignKeyConstraints();          //  外部キーチェックを有効にする

            $users       = User::get();                     //  全ユーザーデータ取得
            $restaurants = Restaurant::get();               //  全店舗データ取得
            $num         = count( Restaurant::get() );      //  全店舗数取得

            $array_start = ["17:00", "18:00", "19:00"];

            foreach ( $users as $user ) {
                foreach( $restaurants as $restaurant ) {
                    if ( random_int( 1, 10 ) <= 3 ) {       //  3割の確率で予約入れる
                        $number = random_int( 1, 8 );       //  予約人数

                        $day_offset = random_int( 1, 10 );  //  現在から何日先か
                        $reserve_date = date('Y-m-d', strtotime("+{$day_offset} day")); //  予約日

                        $start_time = $array_start[ random_int( 0,  2 ) ];   //  開始時間

                        $param = [
                            'user_id'       => $user->id,           //  ユーザー名
                            'restaurant_id' => $restaurant->id,     //  店舗ID
                            'number'        => $number,             //  利用人数
                            'reserve_date'  => $reserve_date,       //  予約日
                            'start_time'    => $start_time          //  開始時間
                        ];
            
                        Reserve::create( $param );                  //  レコード追加
                    }
                }
            }

        } catch ( Exception $e ) {
            echo $e->getMessage(), "\n";
        }
    }
}
