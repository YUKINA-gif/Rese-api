<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

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
