<?php

namespace Tests\Feature;

use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_an_subject()
    {
        $response = $this->postJson('/api/subjects', [
            'descricao' => 'Descrição de Teste',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['descricao' => 'Descrição de Teste']);

        $this->assertDatabaseHas('assuntos', ['descricao' => 'Descrição de Teste']);
    }

    /** @test */
    public function can_list_subjects()
    {
        Subject::factory()->create(['descricao' => 'Descrição 1']);
        Subject::factory()->create(['descricao' => 'Descrição 2']);

        $response = $this->getJson('/api/subjects');

        $response->assertStatus(200)
            ->assertJsonFragment(['descricao' => 'Descrição 1'])
            ->assertJsonFragment(['descricao' => 'Descrição 2']);
    }

    /** @test */
    public function can_view_an_subject()
    {
        $subject = Subject::factory()->create();

        $response = $this->getJson("/api/subjects/{$subject->cod_as}");

        $response->assertStatus(200)
            ->assertJsonFragment(['descricao' => $subject->descricao]);
    }

    /** @test */
    public function can_update_an_subject()
    {
        $subject = Subject::factory()->create();

        $response = $this->putJson("/api/subjects/{$subject->cod_as}", [
            'descricao' => 'Descrição Atualizada',
        ]);

        $response->assertStatus(204)
            ->assertNoContent();

        $this->assertDatabaseHas('assuntos', ['descricao' => 'Descrição Atualizada']);
    }

    /** @test */
    public function can_delete_an_subject()
    {
        $subject = Subject::factory()->create();

        $response = $this->deleteJson("/api/subjects/{$subject->cod_as}");

        $response->assertStatus(204)
            ->assertNoContent();

        $this->assertDatabaseMissing('assuntos', ['cod_as' => $subject->cod_as]);
    }
}
