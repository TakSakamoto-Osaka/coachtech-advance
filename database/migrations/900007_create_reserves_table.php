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
        //  予約テーブル
        Schema::create('reserves', function (Blueprint $table) {
            $table->id();

            $table->foreignId('restaurant_id')->constrained('restaurants')  ->comment('店舗ID');
            $table->foreignId('user_id')->constrained('users')              ->comment('ユーザーID');
            $table->tinyinteger('number')                                   ->comment('利用人数');
            $table->date('reserve_date')                                    ->comment('利用日');
            $table->time('start_time')                                      ->comment('開始時間');

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
        Schema::dropIfExists('reserves');
    }
};
