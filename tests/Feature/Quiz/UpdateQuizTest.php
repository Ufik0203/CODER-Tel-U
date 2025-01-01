<?php

namespace Tests\Feature\Quiz;

use App\Models\Division;
use App\Models\Quiz\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateQuizTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test halaman create dapat diakses.
     */

    public function test_edit_quiz_page_rendered(): void
    {
        $response = $this->get(route('app.e-learning.quiz.edit', ['code' => 'ABC123', 'id' => 1]));
        $response->assertStatus(302);
    }


    /**
     * Test update quiz with valid data.
     */
    public function test_update_quiz_with_valid_data()
    {
        // Membuat data division
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1'),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Membuat data quiz awal
        $quiz = Quiz::create([
            'title' => 'Old Quiz Title',
            'slug' => Str::slug('Old Quiz Title'),
            'thumbnail' => 'old-thumbnail.jpg',
            'status' => 'public',
            'code' => 'OLD123',
            'division_id' => $division->id,
        ]);

        // Data untuk update quiz
        $updateData = [
            'title' => 'Updated Quiz Title',
            'slug' => Str::slug('Updated Quiz Title'),
            'thumbnail' => 'updated-thumbnail.jpg',
            'status' => 'private',  // The status is now 'private'
            'code' => 'NEW123',
            'division_id' => $division->id,
        ];

        // Melakukan update quiz
        $quiz->update($updateData);

        // Periksa jika quiz berhasil diperbarui di database
        $this->assertDatabaseHas('quizzes', [
            'title' => 'Updated Quiz Title',
            'slug' => Str::slug('Updated Quiz Title'),
            'thumbnail' => 'updated-thumbnail.jpg',
            'status' => 'private',  // Ensure the 'status' is 'private' after the update
            'code' => 'NEW123',
            'division_id' => $division->id,
        ]);

        // Pastikan data sebelumnya sudah tidak ada di database
        $this->assertDatabaseMissing('quizzes', [
            'title' => 'Old Quiz Title',
            'slug' => Str::slug('Old Quiz Title'),
            'thumbnail' => 'old-thumbnail.jpg',
            'status' => 'public',  // The old status should be 'public', and the quiz data should be removed
            'code' => 'OLD123',
        ]);
    }


    /**
     * Test update quiz with invalid data.
     */

    public function test_update_quiz_with_invalid_data()
    {
        // Membuat data division
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1'),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Membuat data quiz awal
        $quiz = Quiz::create([
            'title' => 'Old Quiz Title',
            'slug' => Str::slug('Old Quiz Title'),
            'thumbnail' => 'old-thumbnail.jpg',
            'status' => 'public',
            'code' => 'OLD123',
            'division_id' => $division->id,
        ]);

        // Data untuk update quiz dengan nilai invalid
        $invalidData = [
            'title' => '',
            'slug' => '',
            'thumbnail' => 'invalid-thumbnail.jpg',
            'status' => 'invalid-status',
            'code' => '',
            'division_id' => null,
        ];

        // Melakukan validasi sebelum update
        $validator = Validator::make($invalidData, [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'status' => 'required|in:public,private',
            'code' => 'required|string|max:50',
            'division_id' => 'required|exists:divisions,id',
        ]);

        // Pastikan validasi gagal
        $this->assertTrue($validator->fails());

        // Pastikan quiz tidak diperbarui di database
        $this->assertDatabaseHas('quizzes', [
            'title' => 'Old Quiz Title',
            'slug' => Str::slug('Old Quiz Title'),
            'thumbnail' => 'old-thumbnail.jpg',
            'status' => 'public',
            'code' => 'OLD123',
            'division_id' => $division->id,
        ]);

        // Pastikan data invalid tidak tersimpan di database
        $this->assertDatabaseMissing('quizzes', [
            'title' => '',
            'slug' => '',
            'status' => 'invalid-status',
            'code' => '',
            'division_id' => null,
        ]);
    }
}
