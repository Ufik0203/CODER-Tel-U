<?php

namespace Tests\Feature\Meeting;

use App\Models\Division;
use App\Models\Meeting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateMeetingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test halaman create dapat diakses.
     */
    public function test_create_meeting_page_rendered(): void
    {
        $response = $this->get(route('app.e-learning.meeting.create'));
        $response->assertStatus(302);
    }

    /**
     * Test store meeting.
     */
    public function test_store_meeting_with_valid_data()
    {
        // Membuat data division
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1'),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Data input untuk membuat meeting
        $data = [
            'name' => 'Team Meeting',
            'date_time' => '2025-01-01 10:00:00',
            'end_time' => '2025-01-01 12:00:00',
            'type' => 'offline',
            'location' => 'Meeting Room 1',
            'description' => 'Monthly team meeting',
        ];

        // Panggil fungsi create meeting
        $meeting = Meeting::create([
            "slug" => 'team-meeting', // Simulasi slug
            "name" => $data["name"],
            "date_time" => $data["date_time"],
            "end_time" => $data["end_time"],
            "type" => $data["type"],
            "location" => $data["location"] ?? null,
            "description" => $data["description"],
            "status" => "aktif",
            "division_id" => $division->id,
        ]);

        // Assert bahwa meeting berhasil dibuat
        $this->assertDatabaseHas('meetings', [
            'name' => 'Team Meeting',
            'type' => 'offline',
            'status' => 'aktif',
            'division_id' => $division->id,
        ]);

        // Pastikan nilai yang disimpan sesuai
        $this->assertEquals('Team Meeting', $meeting->name);
        $this->assertEquals('aktif', $meeting->status);
    }

    /**
     * Test creating a meeting with missing required fields.
     *
     * @return void
     */
    public function test_store_meeting_with_missing_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Membuat data division
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1'),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);
        // Data yang tidak lengkap
        $data = [
            'name' => 'Team Meeting',
        ];

        // Panggil fungsi create meeting
        Meeting::create([
            "slug" => 'team-meeting',
            "name" => $data["name"],
            "status" => "aktif",
            "division_id" => $division->id,
        ]);
    }
}
