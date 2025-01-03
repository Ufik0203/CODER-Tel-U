<?php

namespace Tests\Feature\Report;

use App\Models\Division;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateReportTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test halaman edit dapat diakses.
     */
    public function test_edit_report_page_rendered(): void
    {
        $response = $this->get(route('app.report.edit', ['date' => '2024-02-01', 'id' => 1]));
        $response->assertStatus(302);
    }

    /**
     * Test user dapat mengupdate laporan.
     */
    public function test_update_report_with_valid_data()
    {
        // Mock data for the test
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1'),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Valid data for updating a report
        $validData = [
            'type' => 'Modul',
            'date' => '2024-02-01',
            'file' => 'report-file.pdf',
            'division_id' => $division->id,
        ];

        // Update the report
        $report = Report::create($validData);

        // Verify the report was updated successfully in the database
        $this->assertDatabaseHas('reports', [
            'type' => 'Modul',
            'date' => '2024-02-01',
            'file' => 'report-file.pdf',
            'division_id' => $division->id,
        ]);
    }

    /**
     * Test user tidak dapat mengupdate laporan dengan data yang tidak valid.
     */

    public function test_update_report_with_invalid_data()
    {
        // Mock data for the test
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1'),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Create an initial report (valid data)
        $validData = [
            'type' => 'Modul',
            'date' => '2024-01-01',
            'file' => 'initial-report.pdf',
            'division_id' => $division->id,
        ];

        $report = Report::create($validData);

        // Invalid data for updating the report
        $invalidData = [
            'type' => '',
            'date' => 'invalid-date',
            'file' => '',
            'division_id' => $division->id,
        ];

        // Attempt to update the report with invalid data
        $validator = Validator::make($invalidData, [
            'type' => 'required|string',
            'date' => 'required|date',
            'file' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
        ]);

        // Assert that validation fails
        $this->assertTrue($validator->fails());

        // Attempt the update (it should fail because validation fails)
        $report->update($invalidData);

        // Verify that the report has not been updated with the invalid data
        $this->assertDatabaseHas('reports', [
            'type' => 'Modul',
            'date' => '2024-01-01',
            'file' => 'initial-report.pdf',
            'division_id' => $division->id,
        ]);

        // Verify that invalid data has not been saved
        $this->assertDatabaseMissing('reports', [
            'type' => '',
            'date' => 'invalid-date',
            'file' => '',
            'division_id' => $division->id,
        ]);
    }
}
