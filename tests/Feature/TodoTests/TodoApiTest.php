<?php

namespace Tests\Feature\TodoTests;

use App\Todo\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoApiTest extends TodoApiTestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function a_todo_can_be_added_to_the_list(): void
    {
        $this->withoutExceptionHandling();

        $user = $this->user();
        $response = $this->actingAs($user)->post(route('todo.api.add'), $this->data());

        $response->assertOk();
        $this->assertCount(1, Todo::all());

        $todo = Todo::first();
        $this->assertEquals($user->id, $todo->user_id);
    }

    /**
     * @test
     */
    public function the_content_cannot_be_empty(): void
    {
        $response = $this->actingAs($this->user())->post(route('todo.api.add'), $this->data([
            'content' => ''
        ]));

        $response->assertStatus(422);
        $json = $response->json('data');
        $this->assertArrayHasKey('content', $json);
    }

    /**
     * @test
     */
    public function the_content_cannot_more_than_255_chars(): void
    {
        $response = $this->actingAs($this->user())->post(route('todo.api.add'), $this->data([
            'content' => str_repeat('a', 256)
        ]));

        $response->assertStatus(422);
        $json = $response->json('data');
        $this->assertArrayHasKey('content', $json);
    }

    /**
     * @test
     */
    public function a_todo_content_can_be_updated(): void
    {

        $user = $this->user();

        $this->actingAs($user)->post(route('todo.api.add'), $this->data());
        $todo = Todo::find(1);
        $this->assertNotNull($todo);

        $response = $this->actingAs($user)->patch(route('todo.api.update', [$todo->id]), $this->data([
            'content' => 'the new content'
        ]));
        $todo->refresh();

        $response->assertOk();
        $this->assertEquals('the new content', $todo->content);
    }

    /**
     * @test
     */
    public function non_existed_todo_content_cannot_be_updated(): void
    {
        $response = $this->actingAs($this->user())->patch(route('todo.api.update', [133]), $this->data());

        $response->assertNotFound();
        $this->assertEquals(0, Todo::count());
    }

    /**
     * @test
     */
    public function a_todo_only_can_be_updated_by_its_owner(): void
    {
        $user = $this->user();
        $attacker = $this->user();

        $this->actingAs($user)->post(route('todo.api.add'), $this->data());
        $todo = Todo::first();
        $this->assertNotNull($todo);

        $response = $this->actingAs($attacker)->patch(route('todo.api.update', [$todo->id]), $this->data([
            'content' => 'new content',
        ]));
        $response->assertForbidden();
        $todo->refresh();
        $this->assertEquals($this->data()['content'], $todo->content);
    }

    /**
     * @test
     */
    public function a_todo_can_be_deleted(): void
    {
        $this->actingAs($this->user())->post(route('todo.api.add'), $this->data());
        $todo = Todo::first();
        $this->assertNotNull($todo);

        $response = $this->delete(route('todo.api.delete', [$todo->id]));
        $response->assertOk();
        $this->assertEquals(0, Todo::count());
    }

    /**
     * @test
     */
    public function non_existed_todo_cannot_be_deleted(): void
    {
        $this->actingAs($this->user())->post(route('todo.api.add'), $this->data());

        $response = $this->delete(route('todo.api.delete', [123]));
        $response->assertNotFound();
        $this->assertEquals(1, Todo::count());
    }

    /**
     * @test
     */
    public function visitor_cannot_add_a_todo(): void
    {
        $result = $this->post(route('todo.api.add'), $this->data());
        $result->assertUnauthorized();
        $this->assertEquals(0, Todo::count());
    }

    /**
     * @test
     */
    public function visitor_cannot_update_a_todo(): void
    {
        $this->actingAs($this->user())->post(route('todo.api.add'), $this->data());
        auth()->logout();

        $response = $this->patch(route('todo.api.update', [123]), [
            'content' => 'good'
        ]);
        $todo = Todo::first();

        $response->assertUnauthorized();
        $this->assertEquals($this->data()['content'], $todo->content);
    }
}
