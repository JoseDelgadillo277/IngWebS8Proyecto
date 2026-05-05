<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Crear roles base con guard_name = web
        foreach (['admin', 'recepcionista', 'odontologo', 'asistente', 'paciente'] as $r) {
            Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
        }

        // 2) Crear/actualizar usuario administrador
        $admin = User::updateOrCreate(
            ['email' => 'admin@artedental.pe'],
            [
                'name'              => 'Administrador',
                'password'          => Hash::make('Admin#123'),
                'email_verified_at' => now(),
                'remember_token'    => Str::random(10),
            ]
        );

        // 3) Asignar rol admin (guard web)
        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
