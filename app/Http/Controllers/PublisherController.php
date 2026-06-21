<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    /**
     * Exibe uma lista de publishers.
     */
    public function index()
    {
        if (!auth()->user()) {
            abort(403, 'Acesso não autorizado');
        }

        $publishers = Publisher::all();
        return view('publishers.index', compact('publishers'));
    }

    /**
     *  Mostra o formulário para criar uma nova publisher.
     */
    public function create()
    {
        $this->requireBibliotecarioOrAdmin();

        return view('publishers.create');
    }

    /**
     * Armazena uma nova publisher no banco de dados.
     */
    public function store(Request $request)
    {
        $this->requireBibliotecarioOrAdmin();

        $request->validate([
            'name' => 'required|string|unique:publishers|max:255',
        ]);

        Publisher::create($request->all());

        return redirect()->route('publishers.index')->with('success', 'Publisher criada com sucesso.');
    }

    /**
     * Exibe uma publisher específica.
     */
    public function show(Publisher $publisher)
    {
        if (!auth()->user()) {
            abort(403, 'Acesso não autorizado');
        }
        
        return view('publishers.show', compact('publisher'));
    }

    /**
     * Mostra o formulário para editar uma publisher existente.
     */
    public function edit(Publisher $publisher)
    {
        $this->requireBibliotecarioOrAdmin();

        return view('publishers.edit', compact('publisher'));
    }

    /**
     * Atualiza uma publisher no banco de dados.
     */
    public function update(Request $request, Publisher $publisher)
    {
        $this->requireBibliotecarioOrAdmin();

        $request->validate([
            'name' => 'required|string|unique:publishers,name,' . $publisher->id . '|max:255',
        ]);

        $publisher->update($request->all());

        return redirect()->route('publishers.index')->with('success', 'Publisher atualizada com sucesso.');
    }

    /**
     * Remove uma publisher do banco de dados
     */
    public function destroy(Publisher $publisher)
    {
        $this->requireBibliotecarioOrAdmin();

        $publisher->delete();

        return redirect()->route('publishers.index')->with('success', 'Publisher excluída com sucesso.');
    }
}
