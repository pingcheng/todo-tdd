<?php

namespace Tests\Feature\TodoTests;

use App\Todo\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoApiMarkAsDoneTest extends TodoApiTestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function visitor_cannot_mark_todo_as_done(): void
    {
        $this->post(route('todo.api.done', [1]))->assertUnauthorized();
    }

    /**
     * @test
     */
    public function visitor_cannot_mark_todo_as_undone(): void
    {
        $this->post(route('todo.api.undone', [1]))->assertUnauthorized();
    }

    /**
     * @test
     */
    public function only_owner_can_mark_todo_as_done(): void
    {
        $owner = $this->user();
        $attacker = $this->user();

        $this->actingAs($owner)->post(route('todo.api.add'), $this->data());
        $response = $this->actingAs($attacker)->post(route('todo.api.done', [1]));

        $response->assertForbidden();
        $json = $this->assertValidApiResponse($response);
        $this->assertEquals(403, $json['status_code']);
    }

    /**
     * @test
     */
    public function only_owner_can_mark_todo_as_undone(): void
    {
        $owner = $this->user();
        $attacker = $this->user();

        $this->actingAs($owner)->post(route('todo.api.add'), $this->data());
        $response = $this->actingAs($attacker)->post(route('todo.api.undone', [1]));

        $response->assertForbidden();
        $json = $this->assertValidApiResponse($response);
        $this->assertEquals(403, $json['status_code']);
    }

    /**
     * @test
     */
    public function owner_can_mark_todo_as_done(): void
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user());
        $this->post(route('todo.api.add'), $this->data());
        $response = $this->post(route('todo.api.done', [1]));
        $this->assertValidApiResponse($response, 200);

        $todo = Todo::first();
        $this->assertNotNull($todo->done_at);
    }

    /**
     * @test
     */
    public function owner_can_mark_done_todo_as_undone(): void
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user());
        $this->post(route('todo.api.add'), $this->data());
        $this->post(route('todo.api.done', [1]));
        $response = $this->post(route('todo.api.undone', [1]));
        $this->assertValidApiResponse($response, 200);

        $todo = Todo::first();
        $this->assertNull($todo->done_at);
    }
}
