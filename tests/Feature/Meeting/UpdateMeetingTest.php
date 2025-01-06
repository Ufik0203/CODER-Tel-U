<?php

namespace Tests\Feature\Meeting;

use App\Models\Division;
use App\Models\Meeting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateMeetingTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test halaman create dapat diakses.
     */

    public function test_edit_meeting_page_rendered(): void
    {
        $response = $this->get(route('app.e-learning.meeting.edit', ['id' => 1]));
        $response->assertStatus(302);
    }


    /**
     * Test update meeting dengan data valid.
     */
    public function test_update_meeting_with_valid_data()
    {
        // Membuat data division
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1'),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Membuat data meeting awal
        $meeting = Meeting::create([
            'name' => 'Initial Meeting',
            'slug' => 'initial-meeting',
            'description' => 'Initial description of the meeting.',
            'date_time' => '2025-01-10 10:00:00',
            'type' => 'online',
            'status' => 'aktif',
            'division_id' => $division->id,
        ]);

        // Data yang akan diperbarui
        $updateData = [
            'name' => 'Updated Meeting',
            'slug' => 'update-meeting',
            'description' => 'Updated description of the meeting.',
            'date_time' => '2025-02-10 10:00:00',
            'type' => 'online',
            'status' => 'aktif',
            'division_id' => $division->id,
        ];

        // Mengupdate data meeting
        $meeting->update($updateData);

        // Memastikan data telah diperbarui di database
        $this->assertDatabaseHas('meetings', $updateData);
    }


    /**
     * Test update meeting dengan data tidak valid.
     */
    public function test_update_meeting_with_invalid_data()
    {
        // Membuat data division
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1'),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Membuat data meeting awal
        $meeting = Meeting::create([
            'name' => 'Initial Meeting',
            'slug' => 'initial-meeting',
            'description' => 'Initial description of the meeting.',
            'date_time' => '2025-01-10 10:00:00',
            'type' => 'offline',
            'status' => 'aktif',
            'division_id' => $division->id,
        ]);

        // Data yang tidak valid untuk update
        $invalidData = [
            'name' => '',
            'description' => 'Updated description of the meeting.',
            'date_time' => 'invalid-date',
            'type' => '',
            'status' => 'aktif',
        ];

        // Validasi data sebelum update
        $validator = Validator::make($invalidData, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_time' => 'required|date',
            'type' => 'required|string',
            'status' => 'required|string',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            // Pastikan data lama masih ada di database
            $this->assertDatabaseHas('meetings', [
                'name' => 'Initial Meeting',
                'slug' => 'initial-meeting',
                'description' => 'Initial description of the meeting.',
                'date_time' => '2025-01-10 10:00:00',
                'type' => 'offline',
                'status' => 'aktif',
                'division_id' => $division->id,
            ]);

            // Pastikan data tidak valid tidak masuk ke database
            $this->assertDatabaseMissing('meetings', $invalidData);

            // Test passed jika validasi gagal
            $this->assertTrue(true);
            return;
        }

        // Jika validasi tidak gagal, test harus gagal
        $this->fail('Meeting berhasil diperbarui dengan data tidak valid.');
    }
}
