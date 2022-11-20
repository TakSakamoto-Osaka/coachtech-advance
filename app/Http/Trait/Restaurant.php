<?php

namespace App\Http\Trait;

use Illuminate\Support\Facades\App;

trait Restaurant
{

    /**
     * 
     * 店舗データ配列のそれぞれのデータに画像を追加する
     * 
     * @param mixed $restaurants    店舗データ配列
     * 
     * @return [type]               画像データを加えた店舗データ配列
     */
    public function addRestaurantImage( $restaurants )
    {
        $s3_bucket = config('aws.bucket');      //  AWS S3バケット
        $results = [];                          //  戻り値レストランデータ配列

        //  店舗情報取得
        foreach ( $restaurants as $restaurant ) {
            //  デプロイ先に応じて画像ファイルの取得場所変更
            if ( App::environment('local') ) {          //  ローカル実行の場合
                $restaurant->img = asset('/storage/images').'/'.$restaurant->img;

            } elseif(App::environment('aws')) {         //  AWS S3の場合
                //  AWS S3バッケットの該当画像のURL取得
                $cmd = "aws s3 presign s3://{$s3_bucket}/images/{$restaurant->img}";
                $opt = [];
                exec($cmd, $opt);
                $restaurant->img = $opt[0];

            } else {                                    //  その他(Heroku等)       
                $restaurant->img = asset('/storage/images').'/'.$restaurant->img;
            }

            array_push( $results, $restaurant );
        }

        return( $results );
    }

    /**
     * 
     * デプロイ先に応じて画像ファイルパス配列を取得する
     * 
     * @param mixed $images     画像ファイル名配列
     * 
     * @return [type]           画像パス配列
     */
    public function getImagePath ( $images )
    {
        $s3_bucket = config('aws.bucket');      //  AWS S3バケット
        $results = [];                          //  画像パスデータ

        //  デプロイ先に応じて画像ファイルの取得場所変更
        if ( App::environment('local') ) {          //  ローカル実行の場合
            foreach( $images as $image ) {
                array_push( $results, asset('/storage/images').'/'.$image->img );
            }

        } elseif(App::environment('aws')) {         //  AWS S3の場合
            foreach( $images as $image ) {
                //  AWS S3バッケットの該当画像のURL取得
                $cmd = "aws s3 presign s3://{$s3_bucket}/images/{$image->img}";
                $opt = [];
                exec($cmd, $opt);
                array_push( $results, $opt[0] );
            }

        } else {                                    //  その他(Heroku等)       
            foreach( $images as $image ) {
                array_push( $results, asset('/storage/images').'/'.$image->img );
            }
        }

        return( $results );
    }
}
