<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AreasTableSeeder::class);                       //  地域
        $this->call(GenresTableSeeder::class);                      //  ジャンル
        $this->call(RestaurantsTableSeeder::class);                 //  店舗
        $this->call(RestaurantTelsTableSeeder::class);              //  店舗電話番号
        //$this->call(RestaurantImagesTableSeeder::class);          //  店舗画像
        
        //$this->call(RestaurantDayOffsTableSeeder::class);           //  店舗営業標準休日
        //$this->call(RestaurantBussinessHoursTableSeeder::class);    //  店舗営業時間テーブルの標準設定時間とラストオーダー時間
        $this->call(RestaurantOverrideHoursTableSeeder::class);     //  店舗臨時営業・休日　標準休日、標準設定時間より優先される
    }
}
