<?php

namespace Tests\Feature\Report;

use App\Models\Division;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class DestroyReportTest extends TestCase
{
    /**
     * Test destroy quiz.
     */
    public function test_destroy_report(): void
    {
        // Membuat data division
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1') . uniqid(),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        $reportData = [
            'type' => 'Modul',
            'date' => '2025-01-10',
            'file' => 'report-file.pdf',
            'division_id' => $division->id,
        ];
        // Create the quiz
        $quiz = Report::create($reportData);
        $this->assertDatabaseHas('reports', ['id' => $quiz->id]);
        $quiz->delete();
        $this->assertDatabaseMissing('reports', ['id' => $quiz->id]);
    }
}
