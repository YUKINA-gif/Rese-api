<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
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
            "area" => "東京"
        ];
        DB::table("areas")->insert(
            $param
        );

        $param = [
            "id" => 2,
            "area" => "大阪"
        ];
        DB::table("areas")->insert(
            $param
        );

        $param = [
            "id" => 3,
            "area" => "福岡"
        ];
        DB::table("areas")->insert(
            $param
        );
    }
}
