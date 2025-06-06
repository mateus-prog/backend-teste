<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends BaseModel
{
    use HasFactory;

    protected $table = 'livros';

    protected $primaryKey = 'cod_l';

    public $timestamps = true;

    protected $fillable = [
        'titulo',
        'editora',
        'edicao',
        'ano_publicacao',
        'preco',
    ];

    protected $casts = [
        'cod_l' => 'integer',
        'titulo' => 'string',
        'editora' => 'string',
        'edicao' => 'integer',
        'ano_publicacao' => 'integer',
        'preco' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $with = [
        'authors', 'subjects',
    ];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'livro_autor', 'livro_cod_l', 'autor_cod_au');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'livro_assunto', 'livro_cod_l', 'assunto_cod_as');
    }
}
