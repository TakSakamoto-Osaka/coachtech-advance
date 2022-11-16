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
        Schema::create('restaurant_override_hours', function (Blueprint $table) {
            $table->id();

            $table->foreignId('restaurant_id')->constrained('restaurants')->comment('店舗ID');
            $table->date('special_day')                                   ->comment('臨時日');
            $table->tinyinteger('temporary_type')                         ->comment('1 : 臨時営業 / 2 : 臨時休日');
            $table->tinyinteger('type')                                   ->comment('1 : 昼間 / 2 : 夜');
            $table->time('open_time')->nullable()                         ->comment('開店時間');
            $table->time('close_time')->nullable()                        ->comment('閉店時間');
            $table->time('lastorder_time')->nullable()                    ->comment('ラストオーダー時間 (この時間以降は予約不可)');

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
        Schema::dropIfExists('restaurant_override_hours');
    }
};
