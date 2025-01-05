<?php

namespace Tests\Feature\Presence;

use App\Models\Division;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyPresenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroy_presence()
    {
        // Buat sebuah division
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => 'division-' . uniqid(),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Buat sebuah presence
        $presence = Presence::create([
            "date_time" => Carbon::now(),
            "section" => "Pertemuan ke 1",
            "status" => "active",
            "division_id" => $division->id,
        ]);

        // Pastikan presence ada di database sebelum dihapus
        $this->assertDatabaseHas('presences', [
            "id" => $presence->id,
            "date_time" => $presence->date_time->format('Y-m-d H:i:s'),
            "section" => $presence->section,
            "status" => $presence->status,
            "division_id" => $division->id,
        ]);

        // Hapus presence
        $presence->delete();

        // Pastikan presence tidak ada di database setelah dihapus
        $this->assertDatabaseMissing('presences', [
            "id" => $presence->id,
            "date_time" => $presence->date_time->format('Y-m-d H:i:s'),
            "section" => $presence->section,
            "status" => $presence->status,
            "division_id" => $division->id,
        ]);
    }
}
