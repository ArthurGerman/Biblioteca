<?php

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Publisher>
 */
class PublisherFactory extends Factory
{
    protected $model = Publisher::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->company, // Gera um nome de empresa único
            'address' => $this->faker->address,
        ];
    }
}
