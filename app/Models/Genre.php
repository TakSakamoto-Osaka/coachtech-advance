<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * ジャンルモデルクラス
 */
class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',             //  ジャンル名
        'order'             //  表示順
    ];

    /**
     * 
     * 全てのジャンルを取得する
     * 
     * @return [type]
     */
    public static function getAll()
    {
        $items = DB::table('genres')->get();

        return( $items );
    }
}
