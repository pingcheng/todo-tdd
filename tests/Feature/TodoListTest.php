<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Throwable;

class TodoListTest extends TestCase
{
    /**
     * @test
     */
    public function a_todo_can_be_added_to_the_list() {
        $response = $this->post('todo', [
            'content' => 'a simple todo'
        ]);

        $response->assertOk();
        $this->assertEquals(1, Todo::all());
    }
}
