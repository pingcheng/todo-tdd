<?php

namespace Tests\Feature;

use App\Todo\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Throwable;

class TodoListTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function a_todo_can_be_added_to_the_list(): void
    {
        $response = $this->post('todo', $this->data());

        $response->assertOk();
        $this->assertCount(1, Todo::all());
    }

    /**
     * @test
     */
    public function the_content_cannot_be_empty(): void
    {
        $response = $this->post('todo', $this->data([
            'content' => ''
        ]));

        $response->assertSessionHasErrors('content');
    }

    /**
     * @test
     */
    public function the_content_cannot_more_than_255_chars(): void
    {
        $response = $this->post('todo', $this->data([
            'content' => str_repeat('a', 256)
        ]));

        $response->assertSessionHasErrors('content');
    }

    /**
     * @test
     */
    public function a_todo_content_can_be_updated(): void
    {
        $this->post('todo', $this->data());
        $todo = Todo::find(1);

        $response = $this->patch("todo/{$todo->id}", $this->data([
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
        $response = $this->patch('todo/133', $this->data());

        $response->assertNotFound();
        $this->assertEquals(0, Todo::count());
    }

    /**
     * @test
     */
    public function a_todo_can_be_deleted(): void
    {
        $this->post('todo', $this->data());
        $todo = Todo::first();

        $response = $this->delete("todo/{$todo->id}");
        $response->assertOk();
        $this->assertEquals(0, Todo::count());
    }

    /**
     * @test
     */
    public function non_existed_todo_cannot_be_deleted(): void
    {
        $this->post('todo', $this->data());

        $response = $this->delete('todo/123');
        $response->assertNotFound();
        $this->assertEquals(1, Todo::count());
    }

    protected function data(array $modifiers = []): array
    {
        return array_merge([
            'content' => 'a simple todo'
        ], $modifiers);
    }
}
