<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Area;


class AreasTableSeeder extends Seeder
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
            Area::truncate();                           //  既存データ全件削除
            Schema::enableForeignKeyConstraints();      //  外部キーチェックを有効にする
    
            //  地域が定義されたCSVファイルを開く
            $fp = fopen( resource_path('/doc/areas.csv'), 'r' );
    
            $line = fgetcsv($fp);               //  タイトル行読み捨て
    
            while($line = fgetcsv($fp)) {
                $param = [
                    'name'  => $line[1],                //  地域名
                    'order' => intval( $line[0] )       //  表示順
                ];
    
                Area::create( $param );                 //  レコード追加
            }
    
            fclose($fp);                        //  CSVファイル閉じる

        } catch ( Exception $e ) {
            echo $e->getMessage(), "\n";
        }
    }
}
