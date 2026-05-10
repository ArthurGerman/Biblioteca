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
        $publishers = Publisher::all();
        return view('publishers.index', compact('publishers'));
    }

    /**
     *  Mostra o formulário para criar uma nova publisher.
     */
    public function create()
    {
        return view('publishers.create');
    }

    /**
     * Armazena uma nova publisher no banco de dados.
     */
    public function store(Request $request)
    {
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
        return view('publishers.show', compact('publisher'));
    }

    /**
     * Mostra o formulário para editar uma publisher existente.
     */
    public function edit(Publisher $publisher)
    {
        return view('publishers.edit', compact('publisher'));
    }

    /**
     * Atualiza uma publisher no banco de dados.
     */
    public function update(Request $request, Publisher $publisher)
    {
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
        $publisher->delete();

        return redirect()->route('publishers.index')->with('success', 'Publisher excluída com sucesso.');
    }
}
