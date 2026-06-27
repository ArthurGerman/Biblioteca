<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'debit'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'debit' => 'decimal:2',
        ];
    }

    public function hasDebt(): bool
    {
        return $this->debit > 0;
    }

    public function addDebt(float $amount): void
    {
        $this->increment('debit', $amount);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'borrowings')
                ->withPivot('id', 'borrowed_at', 'returned_at')
                ->withTimestamps();
    }
    
    public function countOpenBorrowings()
    {
        return $this->books()
                    ->wherePivotNull('returned_at')
                    ->count();
    }

    public function canBorrowMore()
    {
        return $this->countOpenBorrowings() < 5;
    }
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isBibliotecario()
    {
        return $this->role === 'bibliotecario';
    }

    public function isCliente()
    {
        return $this->role === 'cliente';
    }
}
