<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Author;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_a_book()
    {
        $authors = Author::factory()->count(1)->create();
        $subjects = Subject::factory()->count(1)->create();

        $response = $this->postJson('/api/books', [
            'titulo' => 'Livro Teste',
            'editora' => 'Editora Teste',
            'edicao' => 1,
            'ano_publicacao' => 2022,
            'preco' => 99.90,
            'cod_au' => $authors->pluck('cod_au')->toArray(),
            'cod_as' => $subjects->pluck('cod_as')->toArray(),
        ]);

        $this->assertDatabaseHas('livros', ['titulo' => 'Livro Teste']);
    }

    /** @test */
    public function can_list_books()
    {
        Book::factory()->create(['titulo' => 'Livro 1']);
        Book::factory()->create(['titulo' => 'Livro 2']);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonFragment(['titulo' => 'Livro 1'])
            ->assertJsonFragment(['titulo' => 'Livro 2']);
    }

    /** @test */
    public function can_view_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->cod_l}");

        $response->assertStatus(200)
            ->assertJsonFragment(['titulo' => $book->titulo]);
    }

    /** @test */
    public function can_update_a_book()
    {
        $book = Book::factory()->create();

        $authors = Author::factory()->count(1)->create();
        $subjects = Subject::factory()->count(1)->create();

        $response = $this->putJson("/api/books/{$book->cod_l}", [
            'titulo' => 'Livro Atualizado',
            'editora' => 'Nova Editora',
            'edicao' => 2,
            'ano_publicacao' => 2023,
            'preco' => 59.90,
            'cod_au' => $authors->pluck('cod_au')->toArray(),
            'cod_as' => $subjects->pluck('cod_as')->toArray(),
        ]);

        $response->assertStatus(204);

        $this->assertDatabaseHas('livros', ['titulo' => 'Livro Atualizado']);
    }

    /** @test */
    public function can_delete_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/books/{$book->cod_l}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('livros', ['cod_l' => $book->cod_l]);
    }
}
