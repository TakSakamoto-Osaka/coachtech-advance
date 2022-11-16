<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Restaurant;
use App\Models\RestaurantBussinessHour;
use Faker\Factory;

class RestaurantBussinessHoursTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            //  昼営業時間候補
            $bussiness_day = [
                ['open_time'=>'10:30', 'close_time'=>'14:00', 'lastorder_time'=>'13:30'],
                ['open_time'=>'10:30', 'close_time'=>'14:30', 'lastorder_time'=>'14:00'],
                ['open_time'=>'10:30', 'close_time'=>'15:00', 'lastorder_time'=>'14:30'],

                ['open_time'=>'11:00', 'close_time'=>'14:00', 'lastorder_time'=>'13:30'],
                ['open_time'=>'11:00', 'close_time'=>'14:30', 'lastorder_time'=>'14:00'],
                ['open_time'=>'11:00', 'close_time'=>'15:00', 'lastorder_time'=>'14:30'],

                ['open_time'=>'11:30', 'close_time'=>'14:00', 'lastorder_time'=>'13:30'],
                ['open_time'=>'11:30', 'close_time'=>'14:30', 'lastorder_time'=>'14:00'],
                ['open_time'=>'11:30', 'close_time'=>'15:00', 'lastorder_time'=>'14:30'],
            ];

            //  夜営業時間候補
            $bussiness_night = [
                ['open_time'=>'16:00', 'close_time'=>'21:00', 'lastorder_time'=>'20:30'],
                ['open_time'=>'16:00', 'close_time'=>'21:30', 'lastorder_time'=>'21:00'],
                ['open_time'=>'16:00', 'close_time'=>'22:00', 'lastorder_time'=>'21:30'],
                ['open_time'=>'16:00', 'close_time'=>'22:30', 'lastorder_time'=>'22:00'],
                ['open_time'=>'16:00', 'close_time'=>'23:00', 'lastorder_time'=>'22:30'],
                ['open_time'=>'16:00', 'close_time'=>'23:30', 'lastorder_time'=>'23:00'],
                ['open_time'=>'16:00', 'close_time'=>'24:00', 'lastorder_time'=>'23:30'],

                ['open_time'=>'17:00', 'close_time'=>'21:00', 'lastorder_time'=>'20:30'],
                ['open_time'=>'17:00', 'close_time'=>'21:30', 'lastorder_time'=>'21:00'],
                ['open_time'=>'17:00', 'close_time'=>'22:00', 'lastorder_time'=>'21:30'],
                ['open_time'=>'17:00', 'close_time'=>'22:30', 'lastorder_time'=>'22:00'],
                ['open_time'=>'17:00', 'close_time'=>'23:00', 'lastorder_time'=>'22:30'],
                ['open_time'=>'17:00', 'close_time'=>'23:30', 'lastorder_time'=>'23:00'],
                ['open_time'=>'17:00', 'close_time'=>'24:00', 'lastorder_time'=>'23:30'],

                ['open_time'=>'18:00', 'close_time'=>'22:00', 'lastorder_time'=>'21:30'],
                ['open_time'=>'18:00', 'close_time'=>'22:30', 'lastorder_time'=>'22:00'],
                ['open_time'=>'18:00', 'close_time'=>'23:00', 'lastorder_time'=>'22:30'],
                ['open_time'=>'18:00', 'close_time'=>'23:30', 'lastorder_time'=>'23:00'],
                ['open_time'=>'18:00', 'close_time'=>'24:00', 'lastorder_time'=>'23:30'],
            ];

            Schema::disableForeignKeyConstraints();     //  外部キーチェックを無効にする
            RestaurantBussinessHour::truncate();        //  既存データ全件削除
            Schema::enableForeignKeyConstraints();      //  外部キーチェックを有効にする

            //  全店舗のデータ 及び 標準休日データ取得
            $restaurants = DB::table('restaurants as r')
                ->select('r.id', 'd.day_of_week')
                ->leftJoin('restaurant_dayoffs as d', 'r.id', '=', 'd.restaurant_id')
                ->orderBy('r.id')
                ->get();

            foreach( $restaurants as $restaurant ) {
                //  昼営業
                if ( random_int( 1, 10 ) <= 4 ) {               //  4割の確率で昼営業を設定する
                    for ( $i = 0; $i < 3; $i++ ) {          //  平日 / 土曜日 / 日曜・祝日の設定
                        if (  $restaurant->day_of_week == null || $i == 0 ||
                            ( $i == 1 && $restaurant->day_of_week != 7 ) || ( $i == 2 && $restaurant->day_of_week != 1 ) ) {   //  休みでない場合                        
                            $day_no = array_rand($bussiness_day, 1);    //  昼営業時間候補からランダムに1つ取得する
                            
                            $open_time      = $bussiness_day[ $day_no ]["open_time"];
                            $close_time     = $bussiness_day[ $day_no ]["close_time"];
                            $lastorder_time = $bussiness_day[ $day_no ]["lastorder_time"];

                            $param = [
                                'restaurant_id'  => $restaurant->id,    //  店舗ID
                                'day_type'       => $i + 1,             //  1 : 平日 / 2 : 土曜日 / 3 : 日曜・祝日
                                'type'           => 1,                  //  1 : 昼間 / 2 : 夜
                                'open_time'      => $open_time,         //  開店時間
                                'close_time'     => $close_time,        //  閉店時間
                                'lastorder_time' => $lastorder_time     //  ラストオーダー時間 (この時間以降は予約不可)
                            ];

                            RestaurantBussinessHour::create($param);
                        }
                    }
                }
                
                //  夜営業
                for ( $i = 0; $i < 3; $i++ ) {          //  平日 / 土曜日 / 日曜・祝日の設定
                    if (  $restaurant->day_of_week == null || $i == 0 ||
                        ( $i == 1 && $restaurant->day_of_week != 7 ) || ( $i == 2 && $restaurant->day_of_week != 1 ) ) {   //  休みでない場合                        
                        $day_no = array_rand($bussiness_night, 1);    //  夜営業時間候補からランダムに1つ取得する
                        
                        $open_time      = $bussiness_night[ $day_no ]["open_time"];
                        $close_time     = $bussiness_night[ $day_no ]["close_time"];
                        $lastorder_time = $bussiness_night[ $day_no ]["lastorder_time"];

                        $param = [
                            'restaurant_id'  => $restaurant->id,    //  店舗ID
                            'day_type'       => $i + 1,             //  1 : 平日 / 2 : 土曜日 / 3 : 日曜・祝日
                            'type'           => 2,                  //  1 : 昼間 / 2 : 夜
                            'open_time'      => $open_time,         //  開店時間
                            'close_time'     => $close_time,        //  閉店時間
                            'lastorder_time' => $lastorder_time     //  ラストオーダー時間 (この時間以降は予約不可)
                        ];

                        RestaurantBussinessHour::create($param);
                    }
                }
            }

        } catch ( Exception $e ) {
            echo $e->getMessage(), "\n";
        }
    }
}
