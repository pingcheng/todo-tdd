<?php

namespace Tests\Feature\ApiEndPointTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GeneralApiTest extends TestCase
{
    /**
     * @test
     */
    public function a_undefined_end_point_route_return_404(): void
    {
        $response = $this->post('api/random/not_exists',[], $this->apiHeaders());
        $this->assertValidApiResponse($response, 404);
    }

    /**
     * @test
     */
    public function a_undefined_end_point_method_return_405(): void
    {
        $response = $this->post(route('user.api.info'), [], $this->apiHeaders());
        $this->assertValidApiResponse($response, 405);
    }

    /**
     * @test
     */
    public function normal_access_to_undefined_end_point_route_return_404(): void
    {
        $response = $this->post('api/random/not_exists');
        $response->assertNotFound();
        $response->assertSeeText(404);
        $response->assertSeeText('Not Found');
    }

    /**
     * @test
     */
    public function normal_access_to_undefined_end_point_method_return_405(): void
    {
        $this->post(route('user.api.info'))
            ->assertStatus(405)
            ->assertSeeText(405)
            ->assertSeeText('Method Not Allowed');
    }

    private function apiHeaders(): array
    {
        return [
            'content-type' => 'application/json',
            'accept' => 'application/json',
        ];
    }
}
