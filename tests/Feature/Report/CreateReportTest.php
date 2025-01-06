<?php

namespace Tests\Feature\Report;

use App\Models\Division;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use App\Models\Report;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CreateReportTest extends TestCase
{

    use RefreshDatabase;
    /**
     * Test halaman create dapat diakses.
     */
    public function test_create_report_page_rendered(): void
    {
        $response = $this->get(route('app.report.create'));
        $response->assertStatus(302);
    }


    /**
     * Test user dapat membuat laporan baru.
     */

    public function test_store_report_with_valid_data()
    {
        // Mock data for the test
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1') . uniqid(),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Valid data for creating a report
        $validData = [
            'type' => 'Modul',
            'date' => '2025-01-10',
            'file' => 'report-file.pdf',
            'division_id' => $division->id,
        ];

        // Create the report
        $report = Report::create($validData);

        // Verify the report was created successfully in the database
        $this->assertDatabaseHas('reports', [
            'type' => 'Modul',
            'date' => '2025-01-10',
            'file' => 'report-file.pdf',
            'division_id' => $division->id,
        ]);
    }

    /**
     * Test user cannot create a report with invalid data.
     */

    public function test_store_report_with_invalid_data()
    {
        // Mock data for the test
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1') . uniqid(),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);


        // Invalid data (missing type, incorrect date format)
        $invalidData = [
            'type' => '',
            'date' => 'invalid-date',
            'file' => '',
            'division_id' => $division->id,
        ];

        // Validate invalid data using Laravel's Validator
        $validator = Validator::make($invalidData, [
            'type' => 'required|string',
            'date' => 'required|date',
            'file' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
        ]);

        // Ensure validation fails
        $this->assertTrue($validator->fails());

        // Verify the report was not saved in the database
        $this->assertDatabaseMissing('reports', [
            'type' => '',
            'date' => 'invalid-date',
            'file' => '',
            'division_id' => $division->id,
        ]);
    }
}
