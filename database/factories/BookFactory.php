<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'editora' => $this->faker->company(),
            'edicao' => $this->faker->numberBetween(1, 10),
            'ano_publicacao' => $this->faker->year(),
            'preco' => $this->faker->randomFloat(2, 10, 200),
        ];
    }
}
