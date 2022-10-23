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
        //  店舗営業標準休日テーブル
        Schema::create('restaurant_dayoffs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('restaurant_id')->constrained('restaurants')->comment('店舗ID');
            $table->tinyinteger('day_of_week')                            ->comment('1: 日曜日 / 2 : 月曜日 / ・・・　/ 7 : 土曜日(SQLのdayofweek関数の値)');

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
        Schema::dropIfExists('restaurant_dayoffs');
    }
};
