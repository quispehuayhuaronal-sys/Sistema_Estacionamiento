<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    private function verificarAdministrador(): void
    {
        abort_unless(
            auth()->check() && auth()->user()->role === 'administrador',
            403,
            'Solo el administrador puede gestionar usuarios.'
        );
    }

    public function index()
    {
        $this->verificarAdministrador();

        $usuarios = User::orderBy('name')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $this->verificarAdministrador();

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['administrador', 'operador', 'supervisor'])],
        ]);

        User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'role' => $datos['role'],
            'activo' => true,
        ]);

        return redirect()->route('usuarios.index')
            ->with('mensaje', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $usuario)
    {
        $this->verificarAdministrador();

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($usuario->id),
            ],
            'role' => ['required', Rule::in(['administrador', 'operador', 'supervisor'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $usuario->name = $datos['name'];
        $usuario->email = $datos['email'];
        $usuario->role = $datos['role'];

        if (!empty($datos['password'])) {
            $usuario->password = Hash::make($datos['password']);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')
            ->with('mensaje', 'Usuario actualizado correctamente.');
    }

    public function cambiarEstado(User $usuario)
    {
        $this->verificarAdministrador();

        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')
                ->withErrors(['usuario' => 'No puedes desactivar tu propia cuenta.']);
        }

        $usuario->activo = ! $usuario->activo;
        $usuario->save();

        return redirect()->route('usuarios.index')
            ->with('mensaje', 'Estado del usuario actualizado.');
    }
}