<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use RefreshDatabase;

    public function test_can_create_task()
    {
        $payload = [
            'title'       => 'Prepare hearing bundle',
            'description' => 'Bundle for case ABC',
            'status'      => 'pending',
            'due_at'      => now()->addDay()->toISOString(),
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertCreated()
                 ->assertJsonFragment(['title' => 'Prepare hearing bundle']);

        $this->assertDatabaseHas('tasks', ['title' => 'Prepare hearing bundle']);
    }

    public function test_can_list_tasks()
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertOk()
                 ->assertJsonCount(3);
    }

    public function test_can_update_status()
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $response = $this->patchJson("/api/tasks/{$task->id}/status", [
            'status' => 'completed',
        ]);

        $response->assertOk()
                 ->assertJsonFragment(['status' => 'completed']);

        $this->assertDatabaseHas('tasks', [
            'id'     => $task->id,
            'status' => 'completed',
        ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->postJson('/api/tasks', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'status', 'due_at']);
    }
}
