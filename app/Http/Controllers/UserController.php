<?php

namespace App\Http\Controllers;

use App\Models\OdontologoProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /** Lista de roles permitidos en la app */
    private array $roles = ['admin', 'recepcionista', 'odontologo', 'asistente'];

    /**
     * Lista usuarios del sistema para administrarlos.
     */
    public function index()
    {
        $this->ensureOdontologoProfiles();

        $users = User::latest()->paginate(12);
        $roles = $this->roles;

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        // Se envia la lista de roles para el selector del formulario.
        $roles = $this->roles;

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Valida datos de cuenta y obliga a escoger un rol permitido.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in($this->roles)],
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Asegura que el rol exista antes de asignarlo.
            Role::firstOrCreate(['name' => $data['role'], 'guard_name' => 'web']);
            $user->assignRole($data['role']);

            if ($data['role'] === 'odontologo') {
                OdontologoProfile::firstOrCreate(['user_id' => $user->id]);
            }
        });

        return redirect('/users')->with('ok', 'Usuario creado.');
    }

    public function edit(User $user)
    {
        // Formulario para editar datos basicos y rol del usuario.
        $roles = $this->roles;

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // La regla unique ignora al usuario actual para permitir conservar su email.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in($this->roles)],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            ...(isset($data['password']) && $data['password'] ? ['password' => Hash::make($data['password'])] : []),
        ]);

        if (method_exists($user, 'syncRoles')) {
            Role::firstOrCreate(['name' => $data['role'], 'guard_name' => 'web']);
            $user->syncRoles([$data['role']]);
        }

        if ($data['role'] === 'odontologo') {
            OdontologoProfile::firstOrCreate(['user_id' => $user->id]);
        }

        return redirect('/users')->with('ok', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        // Elimina la cuenta seleccionada desde el mantenimiento de usuarios.
        $user->delete();

        return back()->with('ok', 'Usuario eliminado.');
    }

    private function ensureOdontologoProfiles(): void
    {
        User::role('odontologo')
            ->whereDoesntHave('odontologoProfile')
            ->each(function (User $user) {
                OdontologoProfile::firstOrCreate(['user_id' => $user->id]);
            });
    }
}
