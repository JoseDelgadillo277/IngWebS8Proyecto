<?php

namespace Tests\Feature;

use App\Models\OdontologoProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_odontologo_user_with_profile(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Dra. Ana Perez',
            'email' => 'ana.perez@example.com',
            'password' => 'password',
            'role' => 'odontologo',
        ]);

        $response->assertRedirect('/users');

        $odontologo = User::where('email', 'ana.perez@example.com')->firstOrFail();

        $this->assertTrue($odontologo->hasRole('odontologo'));
        $this->assertTrue(OdontologoProfile::where('user_id', $odontologo->id)->exists());
    }

    public function test_users_index_repairs_odontologo_users_without_profile(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'odontologo', 'guard_name' => 'web']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $odontologo = User::factory()->create();
        $odontologo->assignRole('odontologo');

        $this->assertFalse(OdontologoProfile::where('user_id', $odontologo->id)->exists());

        $this->actingAs($admin)->get(route('users.index'))->assertOk();

        $this->assertTrue(OdontologoProfile::where('user_id', $odontologo->id)->exists());
    }
}
