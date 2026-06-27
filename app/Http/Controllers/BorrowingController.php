<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing; 

class BorrowingController extends Controller
{
    public function store(Request $request, Book $book)
    {
        // Verificar se o livro tem empréstimo em aberto
        if ($book->hasOpenBorrowing()) {
            return redirect()->route('books.show', $book)->with('error', 'Este livro já possui um empréstimo em aberto e não pode ser emprestado novamente.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);


        if ($user->hasDebt()) {
            return redirect()->route('books.show', $book)->with('error', 'Este usuário possui débitos pendentes e não pode realizar novos empréstimos.');
        }
        
        // Verificar se o usuário atingiu o limite de 5 livros
        if (!$user->canBorrowMore()) {
            return redirect()->route('books.show', $book)->with('error', 'Este usuário já possui 5 livros emprestados (limite máximo) e não pode pegar mais empréstimos no momento.');
        }

        Borrowing::create([
            'user_id' => $request->user_id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
        ]);

        return redirect()->route('books.show', $book)->with('success', 'Empréstimo registrado com sucesso.');
    }

    public function returnBook(Borrowing $borrowing)
    {
        if ($borrowing->returned_at) {
            return redirect()->route('books.show', $borrowing->book_id)->with('info', 'Empréstimo já foi devolvido.');
        }

        $dueDate = $borrowing->borrowed_at->copy()->addDays(15);
        $lateDays = now()->greaterThan($dueDate) ? now()->diffInDays($dueDate) : 0;
        $fine = $lateDays * 0.50;

        if ($fine > 0) {
            $borrowing->user->addDebt($fine);
        }

        $borrowing->update([
            'returned_at' => now(),
        ]);

        $message = 'Devolução registrada com sucesso.';
        if ($fine > 0) {
            $message .= " Multa de R$ " . number_format($fine, 2, ',', '.') . " registrada.";
        }

        return redirect()->route('books.show', $borrowing->book_id)->with('success', $message);
    }

    public function userBorrowings(User $user)
    {
        $borrowings = $user->books()->withPivot('borrowed_at', 'returned_at')->get();

        return view('users.show', compact('user', 'borrowings'));
    }
}
