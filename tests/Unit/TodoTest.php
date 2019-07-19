<?php

namespace Tests\Unit;

use App\Todo\Todo;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function a_todo_can_be_created(): void
    {
        $todo = Todo::create($this->data());
        $this->assertNotNull($todo);
    }

    /**
     * @test
     */
    public function content_must_be_provided(): void
    {
        $this->expectException(QueryException::class);

        Todo::create($this->data([
            'content' => null,
        ]));
    }

    /**
     * @test
     */
    public function user_id_must_be_provided(): void
    {
        $this->expectException(QueryException::class);

        Todo::create($this->data([
            'user_id' => null,
        ]));
    }

    private function data(array $modifiers = []) {
        return array_merge([
            'content' => 'new todo',
            'user_id' => 1
        ], $modifiers);
    }
}
