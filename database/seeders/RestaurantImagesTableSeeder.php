<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;
use App\Models\Genre;
use App\Models\Restaurant;
use App\Models\RestaurantImage;

use Faker\Factory;

class RestaurantImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $storage_path = './storage/app/public/images';  //  画像ファイルローカルフォルダパス
        $s3_bucket    = config('aws.bucket');           //  AWS S3バケット

        try {
            Schema::disableForeignKeyConstraints();     //  外部キーチェックを無効にする
            RestaurantImage::truncate();                //  既存データ全件削除
            Schema::enableForeignKeyConstraints();      //  外部キーチェックを有効にする

            //  店舗一覧CSVファイルから店舗画像URL取得
            $array_images = array();

            $fp   = fopen( resource_path('/doc/店舗データ一覧.csv'), 'r' );
            $line = fgetcsv($fp);               //  タイトル行読み捨て

            while($line = fgetcsv($fp)) {
                array_push( $array_images, $line[4] );      //  画像URL文字列を配列に入れる
            }

            fclose($fp);                        //  CSVファイル閉じる

            //  ジャンル画像ファイルから画像ファイルBase64エンコードデータ取得
            $array_sushi_images    = array();
            $array_yakiniku_images = array();
            $array_izakaya_images  = array();
            $array_italian_images  = array();
            $array_ramen_images    = array();

            $fp   = fopen( resource_path('/doc/ジャンル画像.csv'), 'r' );
            $line = fgetcsv($fp);               //  タイトル行読み捨て

            while($line = fgetcsv($fp)) {
                switch( $line[0] ) {
                    case '寿司':
                        array_push( $array_sushi_images, $line[1] );        //  画像Base54文字列を寿司配列に入れる
                        break;

                    case '焼肉':
                        array_push( $array_yakiniku_images, $line[1] );     //  画像Base54文字列を焼肉配列に入れる
                        break;

                    case '居酒屋':
                        array_push( $array_izakaya_images, $line[1] );      //  画像Base54文字列を居酒屋配列に入れる
                        break;

                    case 'イタリアン':
                        array_push( $array_italian_images, $line[1] );      //  画像Base54文字列をイタリアン配列に入れる
                        break;

                    case 'ラーメン':
                        array_push( $array_ramen_images, $line[1] );        //  画像Base54文字列をラーメン配列に入れる
                        break;
                }
            }

            fclose($fp);                        //  CSVファイル閉じる


            //  全店舗のデータ取得し、画像データ生成
            //  既存の画像ファイル消去
            array_map('unlink', glob("{$storage_path}/*.*"));

            //  AWSの場合S3のバケットにもファイルアップロード
            if(App::environment('aws')) {
                $cmd = "aws s3 rm s3://{$s3_bucket}/images/ --recursive";
                exec($cmd);
            }

            $restaurants = DB::table('restaurants as r')
                ->select('r.id', 'r.genre_id', 'g.name')
                ->Join('genres as g', 'r.genre_id', '=', 'g.id')
                ->orderBy('r.id')
                ->get();

            foreach( $restaurants as $restaurant ) {
                $str_id = sprintf('%05d', $restaurant->id);     //  レストランIDの5桁数値文字列

                //  jpegファイルをstorageフォルダに保存
                $data = file_get_contents( $array_images[ $restaurant->id - 1 ] );
                $jpeg_name = "restaurant-{$str_id}-001.jpeg";
                file_put_contents("{$storage_path}/{$jpeg_name}", $data);

                //  AWSの場合S3のバケットにもファイルアップロード
                if(App::environment('aws')) {
                    $cmd = "aws s3 mv {$storage_path}/{$jpeg_name} s3://{$s3_bucket}/images/";
                    exec($cmd);
                    echo $cmd;
                }

                //  1枚目(代表画像)の画像生成
                $param = [
                    'restaurant_id' => $restaurant->id,
                    'order'         => 1,                   //  代表画像
                    'img'           => $jpeg_name,
                ];

                RestaurantImage::create( $param );

                //  2枚目以降の画像生成
                $array_img_nos = range( 0, 24 );            //  ジャンルごとに画像は25枚ある。0〜24の抽選を行うため、0〜24の配列を生成する
                shuffle($array_img_nos);                    //  配列をシャッフルする
                
                $img_num = random_int( 5, 14 );             //  画像生成枚数をランダムに決める
                
                $array_genre_images = null;

                switch( $restaurant->name ) {
                    case '寿司':
                        $array_genre_images = $array_sushi_images;
                        break;

                    case '焼肉':
                        $array_genre_images = $array_yakiniku_images;
                        break;

                    case '居酒屋':
                        $array_genre_images = $array_izakaya_images;
                        break;

                    case 'イタリアン':
                        $array_genre_images = $array_italian_images;
                        break;

                    case 'ラーメン':
                        $array_genre_images = $array_ramen_images;
                        break;
                }

                for ( $i = 0; $i < $img_num; $i++ ) {
                    //  jpegファイル保存
                    $data      = base64_decode($array_genre_images[ $array_img_nos[ $i ] ]);
                    $file_no   = sprintf('%03d', 2 + $i);
                    $jpeg_name = "restaurant-{$str_id}-{$file_no}.jpeg";
                    file_put_contents("{$storage_path}/{$jpeg_name}", $data);

                    //  AWSの場合S3のバケットにもファイルアップロード
                    if(App::environment('aws')) {
                        $cmd = "aws s3 mv {$storage_path}/{$jpeg_name} s3://{$s3_bucket}/images/";
                        exec($cmd);
                        echo $cmd;
                    }

                    $param = [
                        'restaurant_id' => $restaurant->id,
                        'order'         => 2 + $i,          //  表示順(2以降)
                        'img'           => $jpeg_name
                    ];
                    
                    RestaurantImage::create( $param );
                }
            }

        } catch ( Exception $e ) {
            echo $e->getMessage(), "\n";
        }
    }
}
