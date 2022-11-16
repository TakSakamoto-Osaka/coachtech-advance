<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Restaurant;
use App\Models\RestaurantTel;
use Faker\Factory;

class RestaurantTelsTableSeeder extends Seeder
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
            RestaurantTel::truncate();                  //  既存データ全件削除
            Schema::enableForeignKeyConstraints();      //  外部キーチェックを有効にする

            //  全店舗のデータ取得し、休日データ生成
            $restaurants = Restaurant::orderBy('id', 'asc')->get(['id']);

            foreach( $restaurants as $restaurant ) {
                $array = array();                       //  ダミーデータ用配列

                if ( random_int( 1, 10 ) <= 7 ) {       //  7割の確率で電話番号を1つにする
                    $faker = Factory::create('ja_JP');  //  ダミーデータ生成
                    $tel   = $faker->phoneNUmber();
                    array_push( $array, array( "tel" => $tel, "type" => 1 ) );

                } else {                                //  3割の確率で電話番号を2つにする
                    for ( $i = 0; $i < 2; $i++ ) {
                        $faker = Factory::create('ja_JP');  //  ダミーデータ生成
                        $tel   = $faker->phoneNUmber();
                        
                        $type = 1;
                        if ( $i > 0 ) {                 //  2つ目以降の電話番号の場合
                            $type = random_int(2, 3);   //  フリーダイヤル or 携帯番号をランダムに設定
                        }
                        
                        array_push( $array, array( "tel" => $tel, "type" => $type ) );
                    }
                }

                //  ダミーデータ追加
                foreach( $array as $dummy ) {
                    $param = [
                        'restaurant_id' => $restaurant->id,     //  店舗ID
                        'tel'           => $dummy["tel"],       //  電話番号       
                        'type'          => $dummy["type"]       //  電話種類 1 : 通常 / 2 : フリーダイヤル / 3 : 携帯
                    ];
                    
                    RestaurantTel::create($param);
                } 
            }

        } catch ( Exception $e ) {
            echo $e->getMessage(), "\n";
        }
    }
}
