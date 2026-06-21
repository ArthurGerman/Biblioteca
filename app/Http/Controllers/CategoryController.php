<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Exibe uma lista de categorias.
     */
    public function index()
    {
        if (!auth()->user()) {
            abort(403, 'Acesso não autorizado');
        }
        
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     *  Mostra o formulário para criar uma nova categoria.
     */
    public function create()
    {
        $this->requireBibliotecarioOrAdmin();

        return view('categories.create');
    }

    /**
     * Armazena uma nova categoria no banco de dados.
     */
    public function store(Request $request)
    {
        $this->requireBibliotecarioOrAdmin();

        $request->validate([
            'name' => 'required|string|unique:categories|max:255',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso.');
    }

    /**
     * Exibe uma categoria específica.
     */
    public function show(Category $category)
    {
        if (!auth()->user()) {
            abort(403, 'Acesso não autorizado');
        }

        return view('categories.show', compact('category'));
    }

    /**
     * Mostra o formulário para editar uma categoria existente.
     */
    public function edit(Category $category)
    {
        $this->requireBibliotecarioOrAdmin();

        return view('categories.edit', compact('category'));
    }

    /**
     * Atualiza uma categoria no banco de dados.
     */
    public function update(Request $request, Category $category)
    {
        $this->requireBibliotecarioOrAdmin();

        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $category->id . '|max:255',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    /**
     * Remove uma categoria do banco de dados
     */
    public function destroy(Category $category)
    {
        $this->requireBibliotecarioOrAdmin();

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso.');
    }
}
