<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AreasTableSeeder::class);       //  地域
        $this->call(GenresTableSeeder::class);      //  ジャンル
        $this->call(RestaurantsTableSeeder::class);      //  店舗
    }
}
