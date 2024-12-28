<?php

namespace Tests\Feature\Modul;

use App\Models\Division;
use App\Models\ELeaning\Modul;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class UpdateModulTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test halaman create dapat diakses.
     */

    public function test_edit_modul_page_rendered(): void
    {
        $response = $this->get(route('app.e-learning.modul.edit', ['id' => 1]));
        $response->assertStatus(302);
    }


    public function test_update_modul_with_valid_data()
    {
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1'),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        $modul = Modul::create([
            'name' => 'Modul Awal',
            'slug' => Str::slug('Modul Awal'),
            'description' => 'Deskripsi Modul Awal',
            'section' => 'Section Awal',
            'type' => 'Type Awal',
            'link' => 'https://example.com/awal',
            'division_id' => $division->id,
        ]);

        $updateData = [
            'name' => 'Modul Update',
            'slug' => Str::slug('Modul Update'),
            'description' => 'Deskripsi Modul Update',
            'section' => 'Section Update',
            'type' => 'Type Update',
            'link' => 'https://example.com/update',
        ];

        $modul->update($updateData);

        $this->assertDatabaseHas('moduls', $updateData);
    }



    public function test_update_modul_with_invalid_data()
    {
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1'),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        $modul = Modul::create([
            'name' => 'Modul Awal',
            'slug' => Str::slug('Modul Awal'),
            'description' => 'Deskripsi Modul Awal',
            'section' => 'Section Awal',
            'type' => 'Type Awal',
            'link' => 'https://example.com/awal',
            'division_id' => $division->id,
        ]);

        $invalidData = [
            'name' => '',
            'slug' => '',
            'description' => 'Deskripsi Modul Update',
            'section' => '',
            'type' => '',
            'link' => 'invalid-url',
        ];

        $this->expectException(\Illuminate\Database\QueryException::class);
        $modul->update($invalidData);
        $this->assertDatabaseMissing('moduls', $invalidData);
    }
}
