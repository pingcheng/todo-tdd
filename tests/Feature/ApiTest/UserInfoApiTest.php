<?php

namespace Tests\Feature\ApiTest;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserInfoApiTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function visitor_cannot_get_user_info(): void
    {
        $response = $this->get(route('user.api.info'));
        $response->assertUnauthorized();

        $json = $response->json();
        $this->assertEquals(401, $json['status_code']);
    }

    /**
     * @test
     */
    public function logged_in_users_can_get_their_info(): void
    {
        $response = $this->actingAs($this->user())->get(route('user.api.info'));
        $response->assertOk();

        $json = $response->json();
        $this->assertArrayHasKey('data', $json);

        $this->assertArrayHasKey('id', $json['data']);
        $this->assertArrayHasKey('name', $json['data']);
        $this->assertArrayHasKey('avatar', $json['data']);
        $this->assertArrayHasKey('email', $json['data']);
    }
}
