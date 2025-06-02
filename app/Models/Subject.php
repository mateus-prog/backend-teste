<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'assuntos';

    protected $primaryKey = 'cod_as';

    public $timestamps = true;

    protected $fillable = [
        'descricao',
    ];

    protected $casts = [
        'cod_as' => 'integer',
        'descricao' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'livro_assunto', 'assunto_cod_as', 'livro_cod_l');
    }
}
