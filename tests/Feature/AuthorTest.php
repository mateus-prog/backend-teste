<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_an_author()
    {
        $response = $this->postJson('/api/authors', [
            'nome' => 'Autor de Teste',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['nome' => 'Autor de Teste']);

        $this->assertDatabaseHas('autores', ['nome' => 'Autor de Teste']);
    }

    /** @test */
    public function can_list_authors()
    {
        Author::factory()->create(['nome' => 'Autor 1']);
        Author::factory()->create(['nome' => 'Autor 2']);

        $response = $this->getJson('/api/authors');

        $response->assertStatus(200)
            ->assertJsonFragment(['nome' => 'Autor 1'])
            ->assertJsonFragment(['nome' => 'Autor 2']);
    }

    /** @test */
    public function can_view_an_author()
    {
        $author = Author::factory()->create();

        $response = $this->getJson("/api/authors/{$author->cod_au}");

        $response->assertStatus(200)
            ->assertJsonFragment(['nome' => $author->nome]);
    }

    /** @test */
    public function can_update_an_author()
    {
        $author = Author::factory()->create();

        $response = $this->putJson("/api/authors/{$author->cod_au}", [
            'nome' => 'Autor Atualizado',
        ]);

        $response->assertStatus(204)
            ->assertNoContent();

        $this->assertDatabaseHas('autores', ['nome' => 'Autor Atualizado']);
    }

    /** @test */
    public function can_delete_an_author()
    {
        $author = Author::factory()->create();

        $response = $this->deleteJson("/api/authors/{$author->cod_au}");

        $response->assertStatus(204)
            ->assertNoContent();

        $this->assertDatabaseMissing('autores', ['cod_au' => $author->cod_au]);
    }
}
