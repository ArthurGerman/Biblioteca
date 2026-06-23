<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Criar 10 usuários clientes
        User::factory(9)->create([
            'role' => 'cliente',
        ]);
        
    }
}