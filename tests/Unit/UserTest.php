<?php

namespace Tests\Unit;

use Carbon\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ユーザー情報取得テスト
     *
     * @return void
     * @test
     */
    public function user_get()
    {
        $response = $this->get("api/user?email=test@test.com");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);

        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest"
        ];

        DB::table('users')->insert($user);

        $response = $this->get("api/user?email=test@test.com");
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "User got successfully",
            "name" => "test"
        ]);
    }
}
