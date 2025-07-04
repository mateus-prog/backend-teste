<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AuthorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => AuthorResource::collection($this->collection),
            'meta' => [
                'total' => $this->collection->count(),
                'status' => 200,
            ],
        ];
    }
}
