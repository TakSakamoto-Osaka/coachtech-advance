<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminsTableSeeder extends Seeder
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
            Admin::truncate();                          //  既存データ全件削除
            Schema::enableForeignKeyConstraints();      //  外部キーチェックを有効にする

            //  ユーザーが定義されたCSVファイルを開く
            $fp = fopen( resource_path('/doc/admins.csv'), 'r' );

            $line = fgetcsv($fp);               //  タイトル行読み捨て

            while($line = fgetcsv($fp)) {
                $param = [
                    'name'              => $line[1],                //  ユーザー名
                    'email'             => $line[2],                //  メールアドレス
                    'email_verified_at' => date('Y/m/d'),           //  承認日
                    'password'          => Hash::make( $line[3] ),  //  パスワードをハッシュ化
                ];

                Admin::create( $param );        //  レコード追加
            }

            fclose($fp);                        //  CSVファイル閉じる

        } catch ( Exception $e ) {
            echo $e->getMessage(), "\n";
        }
    }
}
