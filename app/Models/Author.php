<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    use HasFactory;

    protected $table = 'autores';

    protected $primaryKey = 'cod_au';

    public $timestamps = true;

    protected $fillable = [
        'nome',
    ];

    protected $casts = [
        'cod_au' => 'integer',
        'nome' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'livro_autor', 'autor_cod_au', 'livro_cod_l');
    }
}
