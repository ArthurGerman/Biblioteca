<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function requireBibliotecarioOrAdmin()
    {
        if (!auth()->check() || (!auth()->user()->isBibliotecario() && !auth()->user()->isAdmin())) {
            abort(403, 'Acesso não autorizado');
        }
    }
}
