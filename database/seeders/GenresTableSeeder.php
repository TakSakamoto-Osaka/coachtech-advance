<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Genre;

class GenresTableSeeder extends Seeder
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
            Genre::truncate();                          //  既存データ全件削除
            Schema::enableForeignKeyConstraints();      //  外部キーチェックを有効にする

            //  ジャンルが定義されたCSVファイルを開く
            $fp = fopen( resource_path('/doc/genres.csv'), 'r' );

            $line = fgetcsv($fp);               //  タイトル行読み捨て

            while($line = fgetcsv($fp)) {
                $param = [
                    'name'  => $line[1],                //  ジャンル名
                    'order' => intval( $line[0] )       //  表示順
                ];

                Genre::create( $param );                //  レコード追加
            }

            fclose($fp);                        //  CSVファイル閉じる

        } catch ( Exception $e ) {
            echo $e->getMessage(), "\n";
        }
    }
}
