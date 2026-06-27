@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5>Bem-vindo, {{ auth()->user()->name }}!</h5>
                    <p>Seu papel: <strong>{{ ucfirst(auth()->user()->role) }}</strong></p>
                </div>
            </div>

            <!-- Navegação de Recursos -->
            <div class="row">
                <!-- Cartão de Livros -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-book"></i> Livros
                            </h5>
                            <p class="card-text">Gerencie os livros da biblioteca</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary">
                                Acessar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Cartão de Autores -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-person"></i> Autores
                            </h5>
                            <p class="card-text">Visualize e gerencie autores</p>
                            <a href="{{ route('authors.index') }}" class="btn btn-primary">
                                Acessar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Cartão de Categorias -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-tag"></i> Categorias
                            </h5>
                            <p class="card-text">Visualize e gerencie categorias</p>
                            <a href="{{ route('categories.index') }}" class="btn btn-primary">
                                Acessar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Cartão de Publishers -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-building"></i> Publishers
                            </h5>
                            <p class="card-text">Visualize e gerencie editoras</p>
                            <a href="{{ route('publishers.index') }}" class="btn btn-primary">
                                Acessar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cartão de Usuários (apenas para admin e bibliotecário) -->
            @if(auth()->user()->isAdmin() || auth()->user()->isBibliotecario())
                <div class="row">
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-people"></i> Usuários
                                </h5>
                                <p class="card-text">Gerencie usuários do sistema</p>
                                <a href="{{ route('users.index') }}" class="btn btn-danger">
                                    Acessar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection