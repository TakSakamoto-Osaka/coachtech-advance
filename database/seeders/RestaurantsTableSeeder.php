<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Restaurant;
use Faker\Factory;

class RestaurantsTableSeeder extends Seeder
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
            Restaurant::truncate();                     //  既存データ全件削除
            Schema::enableForeignKeyConstraints();      //  外部キーチェックを有効にする

            //  ジャンルが定義されたCSVファイルを開く
            $fp = fopen( resource_path('/doc/店舗データ一覧.csv'), 'r' );

            $line = fgetcsv($fp);               //  タイトル行読み捨て

            while($line = fgetcsv($fp)) {
                $faker = Factory::create('ja_JP');          //  ダミーデータ生成
                
                //  閉店日ダミーデータ生成( 3割の確率で店舗に閉店日設定 )
                $closed_day = null;                                     //  閉店日
                if ( random_int( 1, 10 ) <= 3 ) {                       //  閉店設定する場合
                    $closed_day_delta = random_int( 7, 60 );            //  閉店までの日数生成
                    $closed_day       = date( "Y-m-d", strtotime("{$closed_day_delta} day") );
                }

                $param = [
                    'name'              => $line[0],                                    //  店舗名
                    'area_id'           => Area::whereName($line[1])->first()->id,      //  地域ID
                    'genre_id'          => Genre::whereName($line[2])->first()->id,     //  ジャンルID
                    'info'              => $line[3],                                    //  店舗概要
                    'address'           => $line[1].$faker->city.$faker->streetAddress, //  店舗住所
                    'reserve_max_day'   => $faker->randomElement([28,30,56,84]),        //  何日先の予約まで可能か
                    'closed_day'        => $closed_day                                  //  閉店日
                ];
                
                Restaurant::create( $param );                //  レコード追加
            }

            fclose($fp);                        //  CSVファイル閉じる

        } catch ( Exception $e ) {
            echo $e->getMessage(), "\n";
        }
    }
}
