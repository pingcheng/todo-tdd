<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function user() {
        return factory(User::class)->create();
    }

    protected function assertValidApiResponse(TestResponse $response, int $status_code = null): array {
        $json = $response->json();
        $this->assertArrayHasKey('status_code', $json);
        $this->assertArrayHasKey('data', $json);

        if ($status_code !== null) {
            $this->assertEquals($status_code, $json['status_code']);
        }
        return $json;
    }
}
