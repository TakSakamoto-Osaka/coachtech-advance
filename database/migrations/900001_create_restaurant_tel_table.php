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
        Schema::create('restaurant_tels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('restaurant_id')->constrained('restaurants')->comment('店舗ID');
            $table->tinyinteger('tel')                                    ->comment('電話番号');
            $table->tinyinteger('type')                                   ->comment('電話種類 1 : 通常 / 2 : フリーダイヤル / 3 : 携帯');

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
        Schema::dropIfExists('restaurant_tels');
    }
};
