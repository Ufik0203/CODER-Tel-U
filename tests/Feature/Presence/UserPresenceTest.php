<?php

namespace Tests\Feature\Presence;

use App\Models\Division;
use App\Models\Presence;
use App\Models\User;
use App\Models\UserPresence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserPresenceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_it_can_create_user_presence_with_valid_data()
    {
        // Create division
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1') . uniqid(),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Create a user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
            'label' => 'Anggota',
            'division_id' => $division->id,
        ]);


        // Create a presence
        $presence = Presence::create([
            'date_time' => now(),
            'section' => 'Pertemuan 1',
            'status' => 'active',
            'division_id' => $division->id,
        ]);

        // Create a user presence
        $userPresence = UserPresence::create([
            'user_id' => $user->id,
            'presences_id' => $presence->id,
            'status' => 'hadir',
        ]);

        // Assert database contains the created user presence
        $this->assertDatabaseHas('user_presences', [
            'user_id' => $user->id,
            'presences_id' => $presence->id,
            'status' => 'hadir',
        ]);
    }



    public function test_it_can_update_user_presence_status()
    {
        // Create division
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1') . uniqid(),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Create a user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
            'label' => 'Anggota',
            'division_id' => $division->id,
        ]);

        // Create a presence
        $presence = Presence::create([
            'date_time' => now(),
            'section' => 'Pertemuan 1',
            'status' => 'active',
            'division_id' => $division->id,
        ]);

        // Create a user presence
        $userPresence = UserPresence::create([
            'user_id' => $user->id,
            'presences_id' => $presence->id,
            'status' => 'hadir',
        ]);

        // Update the user presence status
        $userPresence->update(['status' => 'izin']);

        // Assert the database has the updated status
        $this->assertDatabaseHas('user_presences', [
            'user_id' => $user->id,
            'presences_id' => $presence->id,
            'status' => 'izin',
        ]);
    }


    public function test_it_can_delete_user_presence()
    {
        // Create division
        $division = Division::create([
            'name' => 'Division 1',
            'slug' => Str::slug('Division 1') . uniqid(),
            'description' => 'Deskripsi Division 1',
            'logo' => 'logo.png',
        ]);

        // Create a user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
            'label' => 'Anggota',
            'division_id' => $division->id,
        ]);

        // Create a presence
        $presence = Presence::create([
            'date_time' => now(),
            'section' => 'Pertemuan 1',
            'status' => 'active',
            'division_id' => $division->id,
        ]);

        // Create a user presence
        $userPresence = UserPresence::create([
            'user_id' => $user->id,
            'presences_id' => $presence->id,
            'status' => 'hadir',
        ]);

        // Delete the user presence
        $userPresence->delete();

        // Assert the database does not have the deleted user presence
        $this->assertDatabaseMissing('user_presences', [
            'user_id' => $user->id,
            'presences_id' => $presence->id,
            'status' => 'hadir',
        ]);
    }
}
