<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Acesso não autorizado');
        }

        $users = \App\Models\User::paginate(10); // Paginação para 10 usuários por página
        return view('users.index', compact('users'));
    }


    public function show(\App\Models\User $user)
    {
        // Só admin pode ver perfil de outros, usuário vê apenas seu próprio
        if (!auth()->user() || (!auth()->user()->isAdmin() && auth()->user()->id !== $user->id)) {
            abort(403, 'Acesso não autorizado');
        }

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\User $user)
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Acesso não autorizado');
        }
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\User $user)
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Acesso não autorizado');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,bibliotecario,cliente',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }
}
