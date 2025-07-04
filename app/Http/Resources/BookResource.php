<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cod_l' => $this->cod_l,
            'titulo' => $this->titulo,
            'editora' => $this->editora,
            'edicao' => $this->edicao,
            'ano_publicacao' => $this->ano_publicacao,
            'preco' => $this->preco,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            
            'cod_au' => $this->whenLoaded('authors', fn () => $this->authors->pluck('cod_au')),
            'cod_as' => $this->whenLoaded('subjects', fn () => $this->subjects->pluck('cod_as')),
        ];
    }
}
