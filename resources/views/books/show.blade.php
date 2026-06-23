@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Detalhes do Livro</h1>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Título:</strong> {{ $book->title }}
        </div>
        <div class="card-body">
            <p><strong>Autor:</strong>
                <a href="{{ route('authors.show', $book->author->id) }}">
                    {{ $book->author->name }}
                </a>
            </p>
            <p><strong>Editora:</strong>
                <a href="{{ route('publishers.show', $book->publisher->id) }}">
                    {{ $book->publisher->name }}
                </a>
            </p>
            <p><strong>Categoria:</strong>
                <a href="{{ route('categories.show', $book->category->id) }}">
                    {{ $book->category->name }}
                </a>
            </p>
            <p><strong>Páginas:</strong>
                {{ $book->pages }}
            </p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <label class="form-label">Capa do Livro</label>
        </div>
        <div class="card-body">
            <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/default-cover.png') }}" alt="Capa do Livro" class="img-fluid">
        </div>
    </div>

    <!-- Formulário para Empréstimos (apenas bibliotecário e admin) -->
    @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isBibliotecario()))
        <div class="card mb-4">
            <div class="card-header">Registrar Empréstimo</div>
            <div class="card-body">
                @if($book->hasOpenBorrowing())
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Livro indisponível:</strong> Este livro já possui um empréstimo em aberto e não pode ser emprestado novamente. 
                        Aguarde a devolução.
                    </div>
                @else
                    <form action="{{ route('books.borrow', $book) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Usuário</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="" selected>Selecione um usuário</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                        {{ !$user->canBorrowMore() ? 'disabled' : '' }}>
                                        {{ $user->name }}
                                        @if(!$user->canBorrowMore())
                                            (Limite de 5 livros atingido)
                                        @else
                                            ({{ $user->countOpenBorrowings() }}/5 livros)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Registrar Empréstimo</button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Histórico de Empréstimos -->
        <div class="card">
            <div class="card-header">Histórico de Empréstimos</div>
            <div class="card-body">
                @if($book->users->isEmpty())
                    <p>Nenhum empréstimo registrado.</p>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Data de Empréstimo</th>
                                <th>Data de Devolução</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($book->users as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route('users.show', $user->id) }}">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td>{{ $user->pivot->borrowed_at }}</td>
                                    <td>{{ $user->pivot->returned_at ?? 'Em Aberto' }}</td>
                                    <td>
                                        @if(is_null($user->pivot->returned_at))
                                            <span class="badge bg-warning">Em Aberto</span>
                                        @else
                                            <span class="badge bg-success">Devolvido</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(is_null($user->pivot->returned_at))
                                            <form action="{{ route('borrowings.return', $user->pivot->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-warning btn-sm">Devolver</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endif

    <a href="{{ route('books.index') }}" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>
@endsection