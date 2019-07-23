<?php

namespace Tests\Feature\TodoTests;

use App\Todo\Todo;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoApiListTest extends TodoApiTestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function visitor_cannot_load_todo_list(): void
    {
        $response = $this->get(route('todo.api.list'));
        $response->assertUnauthorized();
        $json = $this->assertValidApiResponse($response);
        $this->assertEquals(401, $json['status_code']);
    }

    /**
     * @test
     */
    public function user_can_load_todo_list(): void
    {
        $user = $this->user();
        $this->actingAs($user);
        $target = 20;

        for ($i=0; $i<$target; $i++) {
            $this->post(route('todo.api.add'), $this->data());
        }

        $this->assertEquals($target, Todo::count());
        $response = $this->get(route('todo.api.list'));
        $response->assertOk();
        $json = $this->assertValidApiResponse($response);
        $this->assertCount($target, $json['data']);
    }

    /**
     * @test
     */
    public function todo_item_must_contain_these_fields(): void
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user())->post(route('todo.api.add'), $this->data());
        $response = $this->get(route('todo.api.list'));
        $todo = $this->assertValidApiResponse($response, 200)['data'][0];

        $this->assertIsInt($todo['id']);
        $this->assertIsString($todo['content']);
        $this->assertIsInt($todo['done_at']);
    }

    /**
     * @test
     */
    public function user_can_load_todo_list_after_certain_id(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        $limit = (new Todo())->getPerPage();
        $target = $limit * 2 + 1;
        $this->actingAs($this->user());

        for ($i=0; $i<$target; $i++) {
            $this->post(route('todo.api.add'), $this->data());
        }

        $this->assertEquals($target, Todo::count());

        $response = $this->get(route('todo.api.list', [
            'after' => $limit * 2
        ]));
        $response->assertOk();
        $json = $this->assertValidApiResponse($response);
        $this->assertCount(1, $json['data']);
    }

    /**
     * @test
     */
    public function user_cannot_load_todo_list_with_not_numeric_after_id(): void
    {
        $this->actingAs($this->user());
        $response = $this->get(route('todo.api.list', [
            'after' => 'abc',
        ]));
        $this->assertValidApiResponse($response, 400);
    }

    /**
     * @test
     */
    public function user_can_load_todo_list_with_negative_after_id(): void
    {
        $this->actingAs($this->user());

        $this->post(route('todo.api.add'), $this->data());
        $this->post(route('todo.api.add'), $this->data());
        $this->post(route('todo.api.add'), $this->data());

        $response = $this->get(route('todo.api.list', [
            'after' => -10
        ]));

        $json = $this->assertValidApiResponse($response, 200);
        $this->assertCount(3, $json['data']);
    }

    /**
     * @test
     */
    public function user_cannot_load_with_unrecognised_filter(): void
    {
        $this->actingAs($this->user());
        $response = $this->get(route('todo.api.list', [
            'filter' => 'random_filter',
        ]));
        $this->assertValidApiResponse($response, 400);
    }

    /**
     * @test
     */
    public function user_can_load_todo_list_with_done_filter(): void
    {
        $user = $this->user();
        $this->actingAs($user);

        $this->post(route('todo.api.add'), $this->data());
        $todo = Todo::first();

        $this->post(route('todo.api.add'), $this->data());
        $this->post(route('todo.api.add'), $this->data());
        $this->post(route('todo.api.add'), $this->data());

        $this->post(route('todo.api.done', [$todo->id]));
        $response = $this->get(route('todo.api.list', [
            'filter' => 'done'
        ]));
        $json = $this->assertValidApiResponse($response, 200);
        $this->assertCount(1, $json['data']);
        $this->assertEquals($todo->id, $json['data'][0]['id']);
    }

    /**
     * @test
     */
    public function default_listing_would_not_include_done_items(): void
    {
        $this->actingAs($this->user());

        $this->post(route('todo.api.add'), $this->data());
        $this->post(route('todo.api.add'), $this->data());
        $this->post(route('todo.api.add'), $this->data());
        $this->post(route('todo.api.add'), $this->data());
        $this->post(route('todo.api.add'), $this->data());

        $this->post(route('todo.api.done', [1]));
        $response = $this->get(route('todo.api.list'));
        $json = $this->assertValidApiResponse($response, 200);

        $this->assertCount(4, $json['data']);
        $this->assertEquals(2, current($json['data'])['id']);
        $this->assertEquals(5, end($json['data'])['id']);
    }
}
