<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoPageTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function visitor_cannot_visit_todo_page(): void
    {
        $response = $this->get(route('todo.index'));
        $response->assertRedirect(url('login'));
    }

    /**
     * @test
     */
    public function logged_in_user_can_visit_todo_page(): void
    {
        $response = $this->actingAs($this->user())->get(route('todo.index'));
        $response->assertOk();
        $response->assertViewIs('todo.index');
    }

    private function user()
    {
        return factory(User::class)->create();
    }
}
