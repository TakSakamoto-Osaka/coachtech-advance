<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Restaurant;
use App\Models\RestaurantDayOff;
use App\Models\RestaurantBussinessHour;
use App\Models\RestaurantOverrideHour;
use Faker\Factory;

class RestaurantOverrideHoursTableSeeder extends Seeder
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
            RestaurantOverrideHour::truncate();         //  既存データ全件削除
            Schema::enableForeignKeyConstraints();      //  外部キーチェックを有効にする

            //  全店舗のデータ取得
            $restaurants = Restaurant::all();

            foreach( $restaurants as $restaurant ) {
                //  昼の営業をしているか取得
                $buss_hours = RestaurantBussinessHour::where([
                    ['restaurant_id', '=', "{$restaurant->id}"],
                    ['type', '=', '1']
                    ])->get();
                    
                if ( count($buss_hours) > 0 ) {     //  昼の営業がある場合
                    //  昼の臨時営業
                    foreach ( $buss_hours as $buss_hour ) {
                        switch( $buss_hour->day_type ) {
                            case 1:                     //  平日の場合
                                $dayoff = RestaurantDayOff::where( [
                                    ['day_of_week', '>=', 2], ['day_of_week', '<=', 6], ['restaurant_id', '=', $restaurant->id ]
                                ] )->first();    //  平日に休日があるか
                                break;

                            case 2:                     //  土曜日の場合
                                $dayoff = RestaurantDayOff::where( [
                                    ['day_of_week', '=', 7], ['restaurant_id', '=', $restaurant->id ]
                                ])->first();    //  土曜日が休日があるか
                                break;

                            case 3:                     //  日曜日の場合
                                $dayoff = RestaurantDayOff::where( [
                                    ['day_of_week', '=', 1], ['restaurant_id', '=', $restaurant->id ]
                                ])->first();    //  日曜日が休日があるか
                                break;
                        }

                        if ( $dayoff != null ) {    //  営業日変更可能な休日の曜日がある場合
                            for ( $i = 0; $i < 7; $i++ ) {
                                if ( date( 'w', strtotime("+{$i} day") ) + 1 == $dayoff->day_of_week ) {
                                    for ( $j = 0; $j < 4; $j++ ) {          //  4回先の同一曜日まで休日設定(の可能性)
                                        if ( random_int( 1, 10 ) <= 4 ) {   //  4割の確率で臨時営業に設定
                                            $k = $i + $j * 7;                   //  同一曜日
                                            $tmp_open_day = date('Y-m-d', strtotime("+{$k} day"));
                                            $param = [
                                                'restaurant_id'  => $restaurant->id,            //  店舗ID
                                                'special_day'    => $tmp_open_day,              //  臨時日
                                                'temporary_type' => 1,                          //  1 : 臨時営業 / 2 : 臨時休日
                                                'type'           => 1,                          //  1 : 昼間 / 2 : 夜
                                                'open_time'      => $buss_hour->open_time,      //  開店時間
                                                'close_time'     => $buss_hour->close_time,     //  閉店時間
                                                'lastorder_time' => $buss_hour->lastorder_time, //  ラストオーダー時間 (この時間以降は予約不可)
                                            ];
                                            RestaurantOverrideHour::create($param);
                                        }
                                    }
                                }
                            }
                            break;
                        }
                    }

                    //  昼の臨時休業
                    //  平日の休日取得
                    $dayoff = RestaurantDayOff::where( [
                        ['day_of_week', '>=', 2], ['day_of_week', '<=', 6], ['restaurant_id', '=', $restaurant->id ]
                    ] )->first();

                    if ( $dayoff != null ) {
                        //  休日設定の曜日の除く
                        $week = [];
                        foreach( [ 2, 3, 4, 5, 6 ] as $elem ) {
                            if ( $elem != $dayoff->day_of_week ) {
                                array_push( $week, $elem );
                            }
                        }
                    } else {
                        $week = [ 2, 3, 4, 5, 6 ];
                    }

                    shuffle($week);                 //  営業日の曜日をシャッフル
                        
                    for ( $i = 0; $i < 3; $i++ ) {
                        if ( random_int( 1, 10 ) <= 4 ) {   //  4割の確率で臨時休業に設定
                            for ( $j = 0; $j < 7; $j++ ) {
                                if ( date( 'w', strtotime("+{$j} day") ) + 1 == $week[$i] ) {   //  臨時休業候補と同一曜日
                                    $k = $j + $i * 7;                   //  同一曜日
                                    $tmp_open_day = date('Y-m-d', strtotime("+{$k} day"));
                                    $param = [
                                        'restaurant_id'  => $restaurant->id,            //  店舗ID
                                        'special_day'    => $tmp_open_day,              //  臨時日
                                        'temporary_type' => 2,                          //  1 : 臨時営業 / 2 : 臨時休日
                                        'type'           => 1,                          //  1 : 昼間 / 2 : 夜
                                        'open_time'      => null,      //  開店時間
                                        'close_time'     => null,     //  閉店時間
                                        'lastorder_time' => null, //  ラストオーダー時間 (この時間以降は予約不可)
                                    ];
                                    RestaurantOverrideHour::create($param);
                                    break;
                                }
                            }
                        }
                    }
                }

                //  夜の臨時営業
                $dayoff = RestaurantDayOff::where(
                    'restaurant_id', '=', $restaurant->id
                )->first();                           //  休日があるか取得
                
                if ( $dayoff != null ) {                //  休日がある場合
                    for ( $i = 0; $i < 3; $i++ ) {
                        if ( random_int( 1, 10 ) <= 4 ) {   //  4割の確率で臨時営業に設定
                            for ( $j = 0; $j < 7; $j++ ) {
                                if ( date( 'w', strtotime("+{$j} day") ) + 1 == $dayoff->day_of_week ) {   //  臨時営業候補と同一曜日
                                    $k = $j + $i * 7;                   //  同一曜日
                                    $tmp_open_day = date('Y-m-d', strtotime("+{$k} day"));

                                    $buss_hours = RestaurantBussinessHour::where([
                                        ['restaurant_id', '=', "{$restaurant->id}"],
                                        ['type', '=', '2'],
                                        ['day_type', '=', 1]
                                    ])->first();            //  臨時営業時間は平日の営業時間と同じ

                                    $param = [
                                        'restaurant_id'  => $restaurant->id,                //  店舗ID
                                        'special_day'    => $tmp_open_day,                  //  臨時日
                                        'temporary_type' => 1,                              //  1 : 臨時営業 / 2 : 臨時休日
                                        'type'           => 2,                              //  1 : 昼間 / 2 : 夜
                                        'open_time'      => $buss_hours->open_time,         //  開店時間
                                        'close_time'     => $buss_hours->close_time,        //  閉店時間
                                        'lastorder_time' => $buss_hours->lastorder_time,    //  ラストオーダー時間 (この時間以降は予約不可)
                                    ];
                                    RestaurantOverrideHour::create($param);
                                    break;
                                }
                            }
                        }
                    }
                }
                
                //  夜の臨時休業
                //  平日の休日取得
                $dayoff = RestaurantDayOff::where( [
                    ['day_of_week', '>=', 2], ['day_of_week', '<=', 6], ['restaurant_id', '=', $restaurant->id ]
                ] )->first();

                if ( $dayoff != null ) {
                    //  休日設定の曜日の除く
                    $week = [];
                    foreach( [ 2, 3, 4, 5, 6 ] as $elem ) {
                        if ( $elem != $dayoff->day_of_week ) {
                            array_push( $week, $elem );
                        }
                    }
                } else {
                    $week = [ 2, 3, 4, 5, 6 ];
                }                
                
                shuffle($week);                 //  営業日の曜日をシャッフル

                for ( $i = 0; $i < 3; $i++ ) {
                    if ( random_int( 1, 10 ) <= 4 ) {   //  4割の確率で臨時休業に設定
                        for ( $j = 0; $j < 7; $j++ ) {
                            if ( date( 'w', strtotime("+{$j} day") ) + 1 == $week[$i] ) {   //  臨時営業候補と同一曜日
                                $k = $j + $i * 7;                   //  同一曜日
                                $tmp_open_day = date('Y-m-d', strtotime("+{$k} day"));
                                $param = [
                                    'restaurant_id'  => $restaurant->id,            //  店舗ID
                                    'special_day'    => $tmp_open_day,              //  臨時日
                                    'temporary_type' => 2,                          //  1 : 臨時営業 / 2 : 臨時休日
                                    'type'           => 2,                          //  1 : 昼間 / 2 : 夜
                                    'open_time'      => null,                       //  開店時間
                                    'close_time'     => null,                       //  閉店時間
                                    'lastorder_time' => null,                       //  ラストオーダー時間 (この時間以降は予約不可)
                                ];
                                RestaurantOverrideHour::create($param);
                                break;
                            }
                        }
                    }
                }
            }
            
        } catch ( Exception $e ) {
            echo $e->getMessage(), "\n";
        }
    }
}
