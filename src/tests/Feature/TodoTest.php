<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_todos_index(): void
    {
        $todo = Todo::create([
            'title' => 'テスト用タスク',
            'description' => 'テスト用詳細',
        ]);

        $response = $this->get('/todos');

        $response->assertStatus(200);
        $response->assertSee('テスト用タスク');
    }

    public function test_can_create_todo(): void
    {
        $payload = [
            'title' => '新しい買い物タスク',
            'description' => '牛乳と卵を買う',
            'due_date' => '2026-12-31',
        ];

        $response = $this->post('/todos', $payload);

        $response->assertRedirect('/todos');
        $this->assertDatabaseHas('todos', [
            'title' => '新しい買い物タスク',
            'description' => '牛乳と卵を買う',
            'is_completed' => false,
        ]);
    }

    public function test_cannot_create_todo_without_title(): void
    {
        $response = $this->post('/todos', [
            'title' => '',
        ]);

        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseCount('todos', 0);
    }

    public function test_can_toggle_todo_completion(): void
    {
        $todo = Todo::create([
            'title' => 'トグルテスト',
            'is_completed' => false,
        ]);

        $response = $this->patch("/todos/{$todo->id}/toggle");
        $response->assertRedirect();

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'is_completed' => true,
        ]);

        // もう一度トグルで未完了に戻る
        $this->patch("/todos/{$todo->id}/toggle");
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'is_completed' => false,
        ]);
    }

    public function test_can_update_todo(): void
    {
        $todo = Todo::create([
            'title' => '更新前タスク',
            'description' => '更新前メモ',
            'is_completed' => false,
        ]);

        $response = $this->put("/todos/{$todo->id}", [
            'title' => '更新後タスク',
            'description' => '更新後メモ',
            'is_completed' => '1',
            'due_date' => '2026-10-30',
        ]);

        $response->assertRedirect('/todos');
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => '更新後タスク',
            'description' => '更新後メモ',
            'is_completed' => true,
        ]);
    }

    public function test_can_delete_todo(): void
    {
        $todo = Todo::create([
            'title' => '削除対象タスク',
        ]);

        $response = $this->delete("/todos/{$todo->id}");

        $response->assertRedirect('/todos');
        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }
}
