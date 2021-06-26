<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            "id" => 1,
            "genre" => "寿司"
        ];
        DB::table("genres")->insert(
            $param
        );

        $param = [
            "id" => 2,
            "genre" => "焼肉"
        ];
        DB::table("genres")->insert(
            $param
        );

        $param = [
            "id" => 3,
            "genre" => "居酒屋"
        ];
        DB::table("genres")->insert(
            $param
        );

        $param = [
            "id" => 4,
            "genre" => "イタリアン"
        ];
        DB::table("genres")->insert(
            $param
        );

        $param = [
            "id" => 5,
            "genre" => "ラーメン"
        ];
        DB::table("genres")->insert(
            $param
        );
    }
}
