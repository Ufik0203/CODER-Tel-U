<?php

namespace Tests\Feature\Task;

use App\Models\Division;
use App\Models\Elearning\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class DestroyTaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test destroy task.
     */
    public function test_destroy_task(): void
    {
        // Membuat data division awal
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1'),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Membuat task awal
        $task = Task::create([
            'slug' => Str::slug('Task Awal'),
            'name' => 'Task Awal',
            'due_date' => '2024-12-30',
            'section' => 'Section Awal',
            'description' => 'Deskripsi Task Awal',
            'division_id' => $division->id,
        ]);

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
        $task->delete();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
