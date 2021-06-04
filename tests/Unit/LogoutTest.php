<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LogoutTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * [POST]ログアウト
     *
     * 正常系
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_logout()
    {
        $response = $this->post("api/logout");
        $response->assertOk()->assertJsonFragment([
            "auth" => false,
        ]);
    }
}
