<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Restaurant;
use App\Models\RestaurantDayOff;
use Faker\Factory;

class RestaurantDayOffsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            Schema::disableForeignKeyConstraints();     //  外部キーチェックを無効にする
            RestaurantDayOff::truncate();               //  既存データ全件削除
            Schema::enableForeignKeyConstraints();      //  外部キーチェックを有効にする

            //  全店舗のデータ取得し、電話番号生成
            $restaurants = Restaurant::orderBy('id', 'asc')->get(['id']);
            
            foreach( $restaurants as $restaurant ) {
                //  1: 日曜日 / 2 : 月曜日 / ・・・　/ 7 : 土曜日
                $dayoff = random_int( 0, 5 );           //  日曜日〜木曜日で休日設定 0の場合は休日なし

                if ( $dayoff > 0 ) {            //  休日がある場合
                    $param = [
                        'restaurant_id' => $restaurant->id,     //  店舗ID 
                        'day_of_week'   => $dayoff              //  電話種類 1 : 通常 / 2 : フリーダイヤル / 3 : 携帯
                    ];

                    RestaurantDayOff::create($param);
                }
            }

        } catch ( Exception $e ) {
            echo $e->getMessage(), "\n";
        }
    }
}
