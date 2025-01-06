<?php

namespace Tests\Feature\Quiz;

use App\Models\Division;
use App\Models\Quiz\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class DestroyQuizTest extends TestCase
{
    /**
     * Test destroy quiz.
     */
    public function test_destroy_quiz(): void
    {
        // Membuat data division
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1') . uniqid(),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Data untuk quiz yang akan dibuat
        $quizData = [
            'id' => rand(1, 1000),
            'title' => 'Sample Quiz',
            'slug' => Str::slug('Sample Quiz') . uniqid(),
            'thumbnail' => 'thumbnail.jpg',
            'status' => 'public',
            'code' => 'ABC123',
            'division_id' => $division->id,
        ];

        // Create the quiz
        $quiz = Quiz::create($quizData);
        $this->assertDatabaseHas('quizzes', ['id' => $quiz->id]);
        $quiz->delete();
        $this->assertDatabaseMissing('quizzes', ['id' => $quiz->id]);
    }
}
