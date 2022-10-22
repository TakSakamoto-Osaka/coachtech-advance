<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  restaurantsテーブル
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();

            $table->string('name',255)                              ->comment('店舗名');
            $table->foreignId('genre_id')->constrained('genres')    ->comment('ジャンルID');
            $table->text('info')                                    ->comment('店舗情報');
            $table->foreignId('area_id')->constrained('areas')      ->comment('地域ID');
            $table->string('address',255)                           ->comment('店舗住所');
            $table->tinyinteger('reserve_max_day')                  ->comment('何日先の予約まで可能か');
            $table->date('closed_day')->nullable()                  ->comment('閉店(予定)日 : この日翌日以降は予約不可');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
};
