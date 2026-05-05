<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /** Lista de roles permitidos en la app */
    private array $roles = ['admin', 'recepcionista', 'odontologo', 'asistente'];

    public function index()
    {
        $users = User::latest()->paginate(12);
        $roles = $this->roles;

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = $this->roles;
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', Rule::in($this->roles)],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Asignar rol
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($data['role']);
        }

        return redirect()->route('users.index')->with('ok', 'Usuario creado.');
    }

    public function edit(User $user)
    {
        $roles = $this->roles;
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role'     => ['required', Rule::in($this->roles)],
        ]);

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            ...(isset($data['password']) && $data['password'] ? ['password' => Hash::make($data['password'])] : []),
        ]);

        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()->route('users.index')->with('ok', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('ok', 'Usuario eliminado.');
    }
}
